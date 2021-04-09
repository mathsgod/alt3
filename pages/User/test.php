<?php

class User_test extends ALT\Page
{
    public function get()
    {

        $t = new App\Testing(3);
        $t->d = "2022-01-02";
        $t->save();
        outp($t);

        $t = new App\Testing(3);
        outp($t);


        return;
        $this->addLib("ckeditor/ckeditor");
        $m = $this->createE(["cke" => "hello"]);
        $m->add("CKE")->ckeditor("cke");
        $this->write($this->createForm($m));

        return;
        $m = $this->createE([
            "image_1" => "",
            "image_2" => ""
        ]);
        $m->add("Image 1")->fileman("image_1");
        $m->add("Image 2")->fileman("image_2");

        $this->write($m);
        return;
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
