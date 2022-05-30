<?php

declare (strict_types=1);
namespace EasyCI20220530\Symplify\VendorPatches\Command;

use EasyCI20220530\Symfony\Component\Console\Input\InputInterface;
use EasyCI20220530\Symfony\Component\Console\Output\OutputInterface;
use EasyCI20220530\Symplify\PackageBuilder\Composer\VendorDirProvider;
use EasyCI20220530\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use EasyCI20220530\Symplify\PackageBuilder\Console\Command\CommandNaming;
use EasyCI20220530\Symplify\VendorPatches\Composer\ComposerPatchesConfigurationUpdater;
use EasyCI20220530\Symplify\VendorPatches\Console\GenerateCommandReporter;
use EasyCI20220530\Symplify\VendorPatches\Differ\PatchDiffer;
use EasyCI20220530\Symplify\VendorPatches\Finder\OldToNewFilesFinder;
use EasyCI20220530\Symplify\VendorPatches\PatchFileFactory;
final class GenerateCommand extends \EasyCI20220530\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var \Symplify\VendorPatches\Finder\OldToNewFilesFinder
     */
    private $oldToNewFilesFinder;
    /**
     * @var \Symplify\VendorPatches\Differ\PatchDiffer
     */
    private $patchDiffer;
    /**
     * @var \Symplify\VendorPatches\Composer\ComposerPatchesConfigurationUpdater
     */
    private $composerPatchesConfigurationUpdater;
    /**
     * @var \Symplify\PackageBuilder\Composer\VendorDirProvider
     */
    private $vendorDirProvider;
    /**
     * @var \Symplify\VendorPatches\PatchFileFactory
     */
    private $patchFileFactory;
    /**
     * @var \Symplify\VendorPatches\Console\GenerateCommandReporter
     */
    private $generateCommandReporter;
    public function __construct(\EasyCI20220530\Symplify\VendorPatches\Finder\OldToNewFilesFinder $oldToNewFilesFinder, \EasyCI20220530\Symplify\VendorPatches\Differ\PatchDiffer $patchDiffer, \EasyCI20220530\Symplify\VendorPatches\Composer\ComposerPatchesConfigurationUpdater $composerPatchesConfigurationUpdater, \EasyCI20220530\Symplify\PackageBuilder\Composer\VendorDirProvider $vendorDirProvider, \EasyCI20220530\Symplify\VendorPatches\PatchFileFactory $patchFileFactory, \EasyCI20220530\Symplify\VendorPatches\Console\GenerateCommandReporter $generateCommandReporter)
    {
        $this->oldToNewFilesFinder = $oldToNewFilesFinder;
        $this->patchDiffer = $patchDiffer;
        $this->composerPatchesConfigurationUpdater = $composerPatchesConfigurationUpdater;
        $this->vendorDirProvider = $vendorDirProvider;
        $this->patchFileFactory = $patchFileFactory;
        $this->generateCommandReporter = $generateCommandReporter;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName(\EasyCI20220530\Symplify\PackageBuilder\Console\Command\CommandNaming::classToName(self::class));
        $this->setDescription('Generate patches from /vendor directory');
    }
    protected function execute(\EasyCI20220530\Symfony\Component\Console\Input\InputInterface $input, \EasyCI20220530\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $vendorDirectory = $this->vendorDirProvider->provide();
        $oldAndNewFileInfos = $this->oldToNewFilesFinder->find($vendorDirectory);
        $composerExtraPatches = [];
        $addedPatchFilesByPackageName = [];
        foreach ($oldAndNewFileInfos as $oldAndNewFileInfo) {
            if ($oldAndNewFileInfo->isContentIdentical()) {
                $this->generateCommandReporter->reportIdenticalNewAndOldFile($oldAndNewFileInfo);
                continue;
            }
            // write into patches file
            $patchFileRelativePath = $this->patchFileFactory->createPatchFilePath($oldAndNewFileInfo, $vendorDirectory);
            $composerExtraPatches[$oldAndNewFileInfo->getPackageName()][] = $patchFileRelativePath;
            $patchFileAbsolutePath = \dirname($vendorDirectory) . \DIRECTORY_SEPARATOR . $patchFileRelativePath;
            // dump the patch
            $patchDiff = $this->patchDiffer->diff($oldAndNewFileInfo);
            if (\is_file($patchFileAbsolutePath)) {
                $message = \sprintf('File "%s" was updated', $patchFileRelativePath);
                $this->symfonyStyle->note($message);
            } else {
                $message = \sprintf('File "%s" was created', $patchFileRelativePath);
                $this->symfonyStyle->note($message);
            }
            $this->smartFileSystem->dumpFile($patchFileAbsolutePath, $patchDiff);
            $addedPatchFilesByPackageName[$oldAndNewFileInfo->getPackageName()][] = $patchFileRelativePath;
        }
        $this->composerPatchesConfigurationUpdater->updateComposerJsonAndPrint(\getcwd() . '/composer.json', $composerExtraPatches);
        if ($addedPatchFilesByPackageName !== []) {
            $message = \sprintf('Great! %d new patch files added', \count($addedPatchFilesByPackageName));
            $this->symfonyStyle->success($message);
        } else {
            $this->symfonyStyle->success('No new patches were added');
        }
        return self::SUCCESS;
    }
}
