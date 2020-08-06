<?php


class System_test extends ALT\Page
{
    public function post()
    {
        outP($_POST);
    }


    public function get()
    {
        $form = new ALT\Element\Form();
        //$form->add("cb")->checkbox("cb");
        //$form->add("switch")->switch("switch1");
        
        /*        $cbg = $form->add("CBG")->checkboxGroup();

        $cbg->checkbox("cb", ["a", "b", "c"]);//

        //$form->add("CB")->checkbox("cb");
        */
        // $form->add("Input1")->input("input1")->required();
        //$form->add("Date1")->datePicker("date1")->required();
        $select=$form->add("Select")->select("select1");
        $select->setAttribute("multiple",true);
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

        //   $form->setData(["input1" => "input1", "date1" => "2020-01-01"]);

        $card = new ALT\Element\Card();
        $card->append($form);
        $this->write($card);
        $this->write($card->script());
    }
}
