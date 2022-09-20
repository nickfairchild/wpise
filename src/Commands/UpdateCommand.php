<?php

namespace Nickfairchild\Wpise\Commands;

use Nickfairchild\Wpise\Helpers;
use Symfony\Component\Console\Input\InputOption;

class UpdateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('upgrade')
            ->addOption('plugins', 'p', InputOption::VALUE_NONE, 'Update only the plugins')
            ->addOption('core', 'c', InputOption::VALUE_NONE, 'Update only WordPress core')
            ->setDescription('Update WordPress and plugins');
    }

    public function handle()
    {
        $plugins = $this->option('plugins');
        $core = $this->option('core');

        if (!$core || $plugins) {
            Helpers::write(shell_exec('wp plugin update --all'));
        }

        if (!$plugins || $core) {
            Helpers::write(shell_exec('wp core update'));
            Helpers::write(shell_exec('wp core update-db'));
        }

        exit(0);
    }
}
