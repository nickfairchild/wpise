<?php

namespace Nickfairchild\Wpise\Commands;

use Nickfairchild\Wpise\Helpers;
use Nickfairchild\Wpise\Manifest;

class InstallThemeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('install:theme')
            ->setDescription('Install the projects theme');
    }

    public function handle()
    {
        $theme = Manifest::current()['name'];
        Helpers::step("Installing $theme theme");

        if (file_exists("public/wp-content/themes/wordpress-base-theme/index.php")) {
            shell_exec("mv public/wp-content/themes/wordpress-base-theme public/wp-content/themes/{$theme}");
        }

        $nodePackageManager = $this->checkWhichNodePackageMangerIsInstalled();

        if (file_exists("public/wp-content/themes/{$theme}/index.php")) {
            $files = [
                "public/wp-content/themes/{$theme}/.env",
                "public/wp-content/themes/{$theme}/.env.example",
                "public/wp-content/themes/{$theme}/vite.config.js"
            ];
            Helpers::line('Running `composer install`');
            passthru("cd public/wp-content/themes/{$theme} && composer install");
            if ($nodePackageManager) {
                Helpers::line("Running `{$nodePackageManager} install`");
                passthru("cd public/wp-content/themes/{$theme} && {$nodePackageManager} install");
            }
            passthru("file_exists('public/wp-content/themes/{$theme}/.env') || copy('public/wp-content/themes/{$theme}/.env.example', 'public/wp-content/themes/{$theme}/.env')");
            Helpers::replaceInFile("public/wp-content/themes/{$theme}/style.css", [
                'Theme Name: Theme' => "Theme Name: {$theme}"
            ]);
            foreach ($files as $file) {
                if ($file) {
                    Helpers::replaceInFile($file, [
                        ':site_slug' => $theme,
                    ]);
                }
            }
        } else {
            Helpers::line("$theme theme doesn't exist.");
        }
    }

    protected function checkWhichNodePackageMangerIsInstalled(): string|null
    {
        if (shell_exec('which pnpm')) {
            return 'pnpm';
        } elseif (shell_exec('which yarn')) {
            return 'yarn';
        } elseif (shell_exec('which npm')) {
            return 'npm';
        }

        return false;
    }
}
