<?php

use ALT\Element\Form;

class System_example_test extends ALT\Page
{
    function everything_in_tags($string, $tagname)
    {
        $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
        preg_match($pattern, $string, $matches);
        return $matches[1];
    }

    public function get()
    {

        $c = file_get_contents("https://www.isi.net/reports/?p=1");

        $pattern = "#<\s*?table\b[^>]*>(.*?)</table\b[^>]*>#s";
        preg_match($pattern, $c, $matches);
        $content=$matches[1];
        $a = p("<div>$content</div>");
        print_r($a);
        die();

        $content = $matches[1];
        print_r($content);

        //print_r($c);
        die();
        $form = new Form();
        $form->setData(["test" => 1]);
        $form->add("Form1")->input("test");
        $this->write($form);
    }
}
