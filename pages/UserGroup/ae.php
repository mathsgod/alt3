<?php

class UserGroup_ae extends ALT\Page
{

    public function get()
    {
        $card = $this->createCard();
        $form = $card->addRForm($this->object());
        $form->add("Name")->input("name")->required();
        $form->add("Code")->input("code");
        $form->add("Remark")->textarea("remark");
        $this->write($card);
    }
}
