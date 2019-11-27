<?php

class System_phpunit extends ALT\Page
{
    public function getPHPUnit()
    {

        return glob(getcwd() . "/plugins/phpunit.*")[0];
    }

    public function run()
    {
        // check compser
        $pi = $this->app->pathInfo();
        $phpunit =  $pi["composer_root"] . "/vendor/bin/phpunit";

        //$s = `$phpunit --version`;
        // $autoload = App::SystemPath("autoload.php");
        //$autoload = App::Path("global.inc.php");

        $composer_autoload = $pi["composer_root"] . "/vendor/autoload.php";
        $s = `$phpunit --debug --bootstrap $composer_autoload tests`;

        return ["content" => $s];

        $this->write("<pre>" . $s . "</pre>");
        $this->write("<hr/>");

        //test system
        $version = App::Version();
        $s = `php $phpunit --debug --bootstrap $autoload system/{$version}/tests `;
        $this->write("<pre>" . $s . "</pre>");
    }

    public function get()
    {
        // check compser
        $pi = $this->app->pathInfo();

        if (!is_readable($pi["composer_root"] . "/vendor/phpunit")) {
            $this->callout->warning("PHPUnit not found", "Please using <a href='System/composer?require=phpunit/phpunit'>System/composer</a> to install phpunit/phpunit");
            $this->template = null;
            return;
        }
    }
}
