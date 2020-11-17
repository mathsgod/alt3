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
        $card = $this->createCard();
        $form = $card->addForm([
            "ms1" => [1, 3]
        ]);


        $form->add("input")->input("input1")->required();

        $form->add("timePicker")->timePicker("t1")->required();
        $form->add("timeSelect")->timeSelect("t2");
        $form->add("datetime")->datetime("dt1");
        $form->add("date")->date("d1");
        $form->add("datePicker")->datePicker("d2");
        $form->add("email")->email("email1");


        /*

        $form->add("password")->password("p1");

        $form->add("Multiselect")->multiselect("ms1", App\User::Query(), "username", "user_id");

        $form->add("CB1")->checkbox("cb1");
 */


        /*    $form->add("Input1")->input("input1");
        $form->add("Input2")->input("input2")->required();
        $form->add("Email1")->email("email1");
        $form->add("Email2")->email("email2")->required();

        $form->add("Select1")->select("select1", App\User::Query(), "username", "user_id")->required();

        $form->add("Date1")->date("date1");

        $form->add("TinyMCE1")->tinymce("tinymce1");
 */

        $this->write($card);
    }
}
