<?php

namespace Nickfairchild\Wpise\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DeployCommand extends Command
{
    use DisplaysDeploymentProgress;

    protected function configure()
    {
        $this
            ->setName('deploy')
            ->addArgument('environment', InputArgument::OPTIONAL, 'The environment name')
            ->addOption('fresh-assets', null, InputOption::VALUE_NONE, 'Upload a fresh copy of all assets')
            ->setDescription('Deploy an environment');
    }

    public function handle()
    {
        $this->ensureManifestIsValid();

        // upload plugins
        // upload media
        // backup database
        // upload database
        // import database
        // update urls

//        $deployment = $this->handleCancellations();
    }

    protected function ensureManifestIsValid()
    {
    }
}
