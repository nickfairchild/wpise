<?php

namespace Nickfairchild\Wpise;

class Path
{
    public static function current(): string
    {
        return getcwd();
    }

    public static function manifest()
    {
        return Helpers::app('manifest');
    }

    public static function defaultManifest(): string
    {
        return getcwd().'/manifest.yml';
    }
}
