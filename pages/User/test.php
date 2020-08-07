<?php

class User_test extends ALT\Page
{
    public function get()
    {

        $card = $this->createCard();
        $form = $card->addForm($this->object());
        $form->add("first name")->input("first_name")->required();

        $this->write($card);
        //$this->write($this->createForm("test"));
        return;
        $this->addLib("ckeditor/ckeditor");
        $e = $this->createE(["a" => "A", "b" => "B"]);

        $ckeditor = $e->add("CKEDITOR 1")->ckeditor("a")[0];
        $ckeditor->addConfig("customConfig", "https://cdn.hostlink.com.hk/ckeditor_config_height.js");


        $ckeditor = $e->add("CKEDITOR 1")->ckeditor("b");
        $config = json_decode($ckeditor->attr(":config"), true);
        $config["customConfig"] = "https://cdn.hostlink.com.hk/ckeditor_config_height.js";
        $ckeditor->attr(":config", json_encode($config));

        $this->write($e);




        return;

        $xls = new App\XLSX(App\User::Query()->toArray());
        $xls->add("Username", "username");
        $xls->add("ID", "user_id");
        $xls->render();
    }
}
