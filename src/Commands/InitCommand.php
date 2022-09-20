<?php

namespace Nickfairchild\Wpise\Commands;

use Illuminate\Support\Str;
use Nickfairchild\Wpise\ApplicationFiles;
use Nickfairchild\Wpise\Helpers;
use Nickfairchild\Wpise\Manifest;
use Nickfairchild\Wpise\Path;

class InitCommand extends Command
{
    protected static $defaultName = 'init';

    protected function configure(): void
    {
        $this
            ->setName('init')
            ->setDescription('Initialize a new project in the current directory');
    }

    public function handle(): void
    {
        $siteSlug = $this->determineName();

        Helpers::line('------');
        Helpers::line("Site name: {$siteSlug}");
        Helpers::line('------');
        Helpers::line();

        Helpers::info('This script will replace the above values in all relevant files in the project directory.');

        if (!Helpers::confirm('Modify files?')) {
            exit(0);
        }

        Manifest::fresh([
            'name' => $siteSlug
        ]);

        $this->modifyFiles($siteSlug);

        $this->call('setup');
    }

    protected function determineName(): string
    {
        return Str::slug(Helpers::ask(
            'What is the name of this project',
            basename(Path::current())
        ));
    }

    protected function modifyFiles(string $siteName): void
    {
        $files = ApplicationFiles::get(Path::current());

        foreach ($files as $file) {
            Helpers::replaceInFile($file, [
                ':site_name' => $siteName,
                ':site_slug' => $siteName,
            ]);

            match (true) {
                str_contains($file, 'README.md') => $this->removeReadmeParagraphs($file),
                default => [],
            };
        }
    }

    protected function removeReadmeParagraphs(string $file): void
    {
        $contents = file_get_contents($file);

        file_put_contents(
            $file,
            preg_replace('/<!--delete-->.*<!--\/delete-->/s', '', $contents) ?: $contents
        );
    }
}
