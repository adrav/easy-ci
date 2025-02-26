<?php

declare(strict_types=1);

namespace Symplify\EasyCI\FileSystem;

use Symfony\Component\Filesystem\Filesystem;

final class StaticRelativeFilePathHelper
{
    public static function resolveFromCwd(string $filePath): string
    {
        $filesystem = new Filesystem();
        $relativeFilePathFromCwd = $filesystem->makePathRelative((string) realpath($filePath), getcwd());

        return rtrim($relativeFilePathFromCwd, '/');
    }
}
