<?php

declare (strict_types=1);
namespace EasyCI20220530\Symplify\VendorPatches\Console;

use EasyCI20220530\Symfony\Component\Console\Style\SymfonyStyle;
use EasyCI20220530\Symplify\VendorPatches\ValueObject\OldAndNewFileInfo;
final class GenerateCommandReporter
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $symfonyStyle;
    public function __construct(\EasyCI20220530\Symfony\Component\Console\Style\SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }
    public function reportIdenticalNewAndOldFile(\EasyCI20220530\Symplify\VendorPatches\ValueObject\OldAndNewFileInfo $oldAndNewFileInfo) : void
    {
        $message = \sprintf('Files "%s" and "%s" have the same content. Did you forgot to change it?', $oldAndNewFileInfo->getOldFileRelativePath(), $oldAndNewFileInfo->getNewFileRelativePath());
        $this->symfonyStyle->warning($message);
    }
}
