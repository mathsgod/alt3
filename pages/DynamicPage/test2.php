<?php

class DynamicPage_test2 extends App\Page
{
    public function get()
    {
        $dp = App\DynamicPage::_("b");


        //  $this->data = $dp->data;

        $this->data["arr1"] = [
            [
                "input2" => "arr1 a"
            ],
            [
                "input2" => "arr1 b"
            ]
        ];

        $this->data["rows"] = [
            [
                "input1" => "rows a"
            ],            [
                "input1" => "rows b"
            ],

        ];
    }
}
