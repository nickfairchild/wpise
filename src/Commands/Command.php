<?php

namespace Nickfairchild\Wpise\Commands;

use DateTime;
use Nickfairchild\Wpise\Helpers;
use Nickfairchild\Wpise\Manifest;
use Nickfairchild\Wpise\Path;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand
{
    public InputInterface $input;

    public OutputInterface $output;

    protected DateTime $startedAt;

    public int $rowCount = 0;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->startedAt = new DateTime();

        Helpers::app()->instance('input', $this->input = $input);
        Helpers::app()->instance('output', $this->output = $output);

        $this->configureManifestPath($input);
        $this->configureOutputStyles($output);

        return Helpers::app()->call([$this, 'handle']) ?: 0;
    }

    protected function configureManifestPath(InputInterface $input)
    {
        Helpers::app()->offsetSet('manifest', $input->getOption('manifest') ?? Path::defaultManifest());
    }

    protected function configureOutputStyles(OutputInterface $output): void
    {
        $output->getFormatter()->setStyle(
            'finished',
            new OutputFormatterStyle('green', 'default', ['bold'])
        );
    }
    protected function argument($key)
    {
        $value = $this->input->getArgument($key);

        if ($key == 'environment' && is_null($value)) {
            $value = Manifest::defaultEnvironment();
        }

        return $value;
    }


    protected function option(string $key): mixed
    {
        return $this->input->getOption($key);
    }

    protected function confirmIfProduction($environment, $force = null)
    {
        if (($this->input->hasOption('force') &&
                $this->option('force')) ||
            $environment !== 'production') {
            return;
        }

        if (! Helpers::confirm('You are manipulating the production environment. Are you sure you want to proceed', false)) {
            Helpers::abort('Action cancelled.');
        }
    }

    public function table(array $headers, array $rows, string $style = 'borderless'): void
    {
        Helpers::table($headers, $rows, $style);
    }

    protected function refreshTable(array $headers, array $rows): void
    {
        if ($this->rowCount > 0) {
            Helpers::write(str_repeat("\x1B[1A\x1B[2K", $this->rowCount + 4));
        }

        $this->rowCount = count($rows);

        $this->table($headers, $rows);
    }

    public function menu(string $title, array $choices): mixed
    {
        return Helpers::menu($title, $choices);
    }

    public function call(string $command, array $arguments = []): int
    {
        $arguments['command'] = $command;

        return $this->getApplication()->find($command)->run(
            new ArrayInput($arguments),
            Helpers::app('output')
        );
    }
}
