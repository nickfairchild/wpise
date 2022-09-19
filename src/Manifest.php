<?php

namespace Nickfairchild\Wpise;

use Symfony\Component\Yaml\Yaml;

class Manifest
{
    public static function name(): string
    {
        return static::current()['name'];
    }

    public static function current(): array
    {
        if (! file_exists(Path::manifest())) {
            Helpers::abort(sprintf('Unable to find a manifest at [%s].', Path::manifest()));
        }

        return Yaml::parse(file_get_contents(Path::manifest()));
    }

    public static function defaultEnvironment(): string
    {
        return static::current()['default-environment'] ?? 'local';
    }

    public static function fresh(array $project): void
    {
        static::freshConfiguration($project);
    }

    protected static function freshConfiguration(array $project): void
    {
//        $environments['production'] = array_filter([
//            'url' => '',
//            'ip' => '',
//        ]);

        $environments['staging'] = array_filter([
            'url' => $project['name'].'',
            'ip' => '',
        ]);

        $environments['local'] = array_filter([
            'url' => $project['name'].'.test',
        ]);

        static::write(array_filter([
            'name' => $project['name'],
            'environments' => $environments,
        ]));
    }

    public static function addEnvironment(string $environment, array $config = []): void
    {
        $manifest = static::current();

        if (isset($manifest['environments'][$environment])) {
            Helpers::abort('That environment already exists.');
        }

        $manifest['environments'][$environment] = ! empty($config) ? $config : [];

        $manifest['environments'] = collect(
            $manifest['environments']
        )->sortKeys()->all();

        static::write($manifest);
    }

    public static function deleteEnvironment(string $environment): void
    {
        $manifest = static::current();

        unset($manifest['environments'][$environment]);

        static::write($manifest);
    }

    protected static function write(array $manifest, string|null $path = null): void
    {
        file_put_contents(
            $path ?: Path::manifest(),
            Yaml::dump($manifest, $inline = 20, $spaces = 4)
        );
    }
}
