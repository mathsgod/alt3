<?php

class User_test extends ALT\Page
{
    public $_a = [1, 2];

    public function post()
    {

        outp($_POST);
        die();
    }
    public function get()
    {

//        outp($this->app->user->jwt());
        return;
        $a=new App\User();
        $a->test=1;
        outp($a->Test());
        //create T test

        
        

        return;

        $grid = $this->createGrid([2]);

        $v = $this->createV();
        $v->add("Username", "username");
        $grid->add($v, [0, 0]);
        $this->write($grid);

        $v = $this->createV();
        $v->add("First name", "first_name");
        $grid->add($v, [0, 1]);
        $this->write($grid);

        return;

        $this->write($grid);

        return;
        $v = $this->createV();

        $v->add("content", function () {
            return "<pre v-pre>{abc}</pre>";
        });
        $this->write($v);

        return;
        $t = $this->createT([
            ["name" => 'a', "name1" => "b"]
        ]);
        $t->formCreate(["name" => "t1"]);
        $t->add("name")->input("name")->required();

        $t->add("name1")->input("name1");
        $t->add("name2")->input("name2");

        $this->write($this->createForm($t));

        return;

        $e = $this->createE();

        $e->add("a")->select("user_id")->ds(App\User::Find())->prepend("<option></option>");

        $this->write($e);


        return;
        $box = $this->createBox();

        $box->header("test");
        $box->pinable(true);

        $this->write($box);

        return;

        throw new Exception("test");

        return;
        //print_r($doc->nodes);
        die();

        die();

        $f = "_a";
        $s = $this->$f;

        outp($s);

        return;

        echo property_exists(User_test, "_a");
    }
}
