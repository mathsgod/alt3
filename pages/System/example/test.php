<?php

use ALT\Element\Form;

class System_example_test extends ALT\Page
{
    public function get()
    {

        $form = new Form();
        $form->setData(["test" => 1]);
        $form->add("Form1")->input("test");
        $this->write($form);
    }
}
