<?php

class UserGroup_ae extends ALT\Page
{
    public function post()
    {
        outP($_POST);
        outp($_FILES);
        die();
    }

    public function get()
    {
        $form = $this->createRForm([]);
        $form->add("Name")->input("name")->required();
        $form->add("Code")->input("code");
        $form->add("Remark")->textarea("remark");

        $form->add("File")->upload("file1");

        $this->write($form);
    }
}
