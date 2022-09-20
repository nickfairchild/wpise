<?php

namespace Nickfairchild\Wpise\Commands;

use Illuminate\Filesystem\Filesystem;
use Nickfairchild\Wpise\GitIgnore;
use Nickfairchild\Wpise\Helpers;
use Nickfairchild\Wpise\Manifest;
use Nickfairchild\Wpise\Path;
use Symfony\Component\Console\Input\InputArgument;

class EnvCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('env')
            ->addArgument('environment', InputArgument::REQUIRED, 'The environment name')
            ->setDescription('Create a new environment');
    }

    public function handle()
    {
        Manifest::addEnvironment($environment = $this->argument('environment'), [
            'url' => Helpers::ask("What is the $environment url?"),
        ]);

        $filesystem = new Filesystem();
        $filesystem->copy(Path::current().'/.env', Path::current().'/.env.'.$environment);
        $filesystem->append(Path::current().'/.env.'.$environment, 'SSH_USER='.Helpers::ask("What is the $environment ssh user?").PHP_EOL);
        $filesystem->append(Path::current().'/.env.'.$environment, 'SSH_HOST='.Helpers::ask("What is the $environment server ip?").PHP_EOL);

        GitIgnore::add(['.env.'.$environment]);

        Helpers::info('Environment created successfully.');
    }
}
