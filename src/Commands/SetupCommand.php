<?php

namespace Nickfairchild\Wpise\Commands;

use Illuminate\Support\Str;
use Nickfairchild\Wpise\Helpers;
use Nickfairchild\Wpise\Manifest;
use Symfony\Component\Console\Input\InputArgument;

class SetupCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('setup')
            ->addArgument('environment', InputArgument::OPTIONAL, 'The environment name')
            ->setDescription('Setup the current project');
    }

    public function handle()
    {
        passthru("php -r \"file_exists('.env') || copy('.env.example', '.env');\"");

        $dbPassword = Helpers::secret('What is your DB password?');

        Helpers::replaceInFile('.env', [
            'DB_PASSWORD= ' => "DB_PASSWORD={$dbPassword}",
        ]);

        Helpers::step('Updating .env files');
        $this->updateEnvFiles();

        Helpers::step('Running `composer install`');
        passthru('composer install');

        $this->call('install:theme');

        $this->call('setup:wordpress');

        $this->call('install:plugins');
    }

    protected function updateEnvFiles(): void
    {
        $siteName = Manifest::current()['name'];

        $files = ['.env', '.env.example'];

        foreach ($files as $file) {
            Helpers::replaceInFile($file, [
                ':site_name' => $siteName,
                ':site_slug' => $siteName,
            ]);
        }
    }
}
