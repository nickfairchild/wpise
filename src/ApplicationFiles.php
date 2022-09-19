<?php

namespace Nickfairchild\Wpise;

use Symfony\Component\Finder\Finder;

class ApplicationFiles
{
    public static function get($path)
    {
        return (new Finder())
            ->in($path)
            ->exclude('.idea')
            ->notName('rr')
            ->notPath('/^'.preg_quote('tests', '/').'/')
            ->ignoreVCS(true)
            ->ignoreDotFiles(false)
            ->files();
    }
}
