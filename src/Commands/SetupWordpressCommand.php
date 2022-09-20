<?php

namespace Nickfairchild\Wpise\Commands;

use Nickfairchild\Wpise\Helpers;
use Nickfairchild\Wpise\Manifest;

class SetupWordpressCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('setup:wordpress');
    }

    public function handle()
    {
        $theme = Manifest::current()['name'];

        Helpers::line('Creating DB');
        passthru('wp db create');
        Helpers::line('Install Wordpress');
        passthru('wp core install --skip-email');
        Helpers::line('Creating Pages');
        passthru('wp post create --post_type=page --post_title="Home" --post_status=publish');
        passthru('wp post create --post_type=page --post_title="Blog" --post_status=publish');
        passthru('wp post create --post_type=page --post_title="Contact" --post_status=publish');
        Helpers::line('Creating Posts');
        passthru('curl -N http://loripsum.net/api/5 | wp post generate --post_content --count=10');
        Helpers::line('Updating Options');
        passthru('wp option update blogdescription ""');
        passthru('wp option update date_format "d/m/Y"');
        passthru('wp option update timezone_string "Europe/London"');
        passthru('wp option add WPLANG "en_GB"');
        passthru('wp option update permalink_structure "/%postname%/"');
        passthru('wp option update show_on_front "page"');
        passthru('wp option update page_on_front 4');
        passthru('wp option update page_for_posts 5');
        passthru('wp option update template '.$theme);
        passthru('wp option update stylesheet '.$theme);
        if (file_exists('media')) {
            passthru('wp media import media/*');
        }
    }
}
