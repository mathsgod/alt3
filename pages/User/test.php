<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class User_test extends App\Page
{
    public $_a = [1, 2];

    public function post()
    {

        outp($_POST);
        die();
    }

    public function get()
    {
        $xls = new App\XLSX(App\User::Query());
        $xls->add("Username", "username");
        $xls->add("ID", "user_id");
        $xls->render();
    }
}
