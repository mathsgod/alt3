<?php

use P\HTMLDivElement;

class System_test extends App\Page
{
    public function post()
    {

        return ["data" => [
            "data" => $_POST
        ]];
    }


    public function get()
    {


        $div = new HTMLDivElement();
        $div->setAttribute("test", json_decode([
            "a" => js("testing")
        ]));
        


        $o = new JS\ObjectClass();
        $o->a = 1;
        $o->b = js("function(){
            return 'hello';
        }");

        echo $o;

        return;
        $this->addLib("ckeditor/ckeditor");

        $card = $this->createCard();
        $form = $card->addForm();

        $form->add("test")->input("input1");
        $form->add("ckeditor")->ckeditor("ckeditor");

        $this->write($card);




        return;


        $form = new ALT\Element\Form();
        $form->add("time select")->timeSelect("time1");
        $form->add("time picker")->timePicker("time2");
        $form->add("input")->input("input1");
        $form->add("textarea")->textarea("text1");
        $form->add("password")->password("password1");
        $form->add("number")->inputNumber("num1")->required();


        //$form->add("cb")->checkbox("cb");
        //$form->add("switch")->switch("switch1");

        /*        $cbg = $form->add("CBG")->checkboxGroup();

        $cbg->checkbox("cb", ["a", "b", "c"]);//

        //$form->add("CB")->checkbox("cb");
        */
        // $form->add("Input1")->input("input1")->required();
        //$form->add("Date1")->datePicker("date1")->required();

        /*         $select = $form->add("Select")->select("select1");
        $select->setAttribute("multiple", true);
        $select->required()->option([
            [
                "value" => 1,
                "label" => 1
            ], [
                "value" => 2,
                "label" => 2
            ], [
                "value" => 3,
                "label" => 3
            ]
        ]);
 */
        //   $form->setData(["input1" => "input1", "date1" => "2020-01-01"]);

        $card = new ALT\Element\Card();
        $card->append($form);
        $this->write($card);
        $this->write($card->script());
    }
}
