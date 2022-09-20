<?php

namespace Nickfairchild\Wpise;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GitIgnore
{
    public static function add(array $paths)
    {
        $paths = Arr::wrap($paths);

        if (! file_exists(getcwd().'/.gitignore')) {
            return;
        }

        $contents = file_get_contents(getcwd().'/.gitignore');

        foreach ($paths as $path) {
            if (! Str::contains($contents, $path.PHP_EOL)) {
                $contents .= $path.PHP_EOL;
            }
        }

        file_put_contents(getcwd().'/.gitignore', $contents);
    }
}
