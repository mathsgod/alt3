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
        $form = $this->createRForm([
            "color1" => "#409EFF"
        ]);

        $form->add("Fileman")->fileman("file1");

        $form->add("color picker")->colorPicker("color1");
        $form->add("Select option group")->multiselect("user_id")->optionGroup($langs, "language", App\User::Query());
        //$form->add("Select option group")->select("user_id")->option(App\User::Query(), "username", "user_id");
        $this->write($form);
    }
}
