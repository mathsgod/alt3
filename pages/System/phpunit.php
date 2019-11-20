<?php

class System_phpunit extends ALT\Page {
    public function getPHPUnit() {

        return glob(getcwd() . "/plugins/phpunit.*")[0];
    }

    public function run() {
        // check compser
        $pi=$this->app->pathInfo();
        $phpunit =  $pi["composer_root"]. "/vendor/bin/phpunit";

        //$s = `$phpunit --version`;
        // $autoload = App::SystemPath("autoload.php");
        //$autoload = App::Path("global.inc.php");

        $composer_autoload=$pi["composer_root"]."/vendor/autoload.php";
        $s = `$phpunit --debug --bootstrap $composer_autoload tests`;

        return ["content"=>$s];

        $this->write("<pre>" . $s . "</pre>");
    	$this->write("<hr/>");

    	//test system
    	$version=App::Version();
    	$s = `php $phpunit --debug --bootstrap $autoload system/{$version}/tests `;
    	$this->write("<pre>" . $s . "</pre>");


    }

    public function get() {
        // check compser
        $pi=$this->app->pathInfo();

        if (!is_readable($folder =  $pi["composer_root"]. "/vendor/phpunit")) {
            $this->callout->warning("PHP unit test not found","Please using <a href='System/composer'>System/composer</a> to install.");
            return;
        }

        return;

        //if (!is_readable($folder = $this->getPHPUnit())) {
            //$this->callout->warning("PHP unit test phar not found","Please using <a href='System/update'>System/update</a> to install.");
            //return;
        //}

        $box = $this->createBox();

        $box->header("PHP unit test");
        $box->body();

        $btn = p("button") ;
        $btn->attr("id", "run1");
        $btn->addClass("btn btn-primary");
        $btn->text("Run");
        $box->footer()->append($btn);
        $this->write($box);
    }
}