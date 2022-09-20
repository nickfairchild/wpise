<?php

namespace Nickfairchild\Wpise\Commands;

class InstallPluginsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('install:plugins')
            ->setDescription('Install the base plugins');
    }

    public function handle()
    {
        passthru('wp plugin install ewww-image-optimizer query-monitor --activate');
        passthru('wp plugin install https://drive.google.com/uc?export=download&id=1IezoC6bNRszYfmLRqDd5sKTGEiSKqTHR --activate');
        passthru('wp plugin install https://drive.google.com/uc?export=download&id=1pIvQFKjoWKlwHxG84C3yEnPh-lQfPyMR');
        passthru('wp plugin install wordpress-seo contact-form-7 redirection better-wp-security worker wp-mail-smtp');
    }
}
