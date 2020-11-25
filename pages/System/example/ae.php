<?php

class System_example_ae extends ALT\Page
{
    public function post()
    {
        outp($_POST);
        die();
    }

    public function get()
    {

        $langs = ["en" => "EN", "zh-hk" => "HK"];
        $form = $this->createRForm([]);

        $form->add("Select option group")->select("user_id")->optionGroup($langs, "language", App\User::Query());
        //$form->add("Select option group")->select("user_id")->option(App\User::Query(), "username", "user_id");
        $this->write($form);
    }
}
