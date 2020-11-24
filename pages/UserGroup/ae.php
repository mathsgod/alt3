<?php

class UserGroup_ae extends ALT\Page
{

    public function get()
    {
        $form = $this->createRForm();
        $form->add("Name")->input("name")->required();
        $form->add("Code")->input("code");
        $form->add("Remark")->textarea("remark");
        
        $this->write($form);
    }
}
