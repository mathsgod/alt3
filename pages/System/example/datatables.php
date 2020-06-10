<?php
use App\User;

class System_example_datatables extends ALT\Page
{

    public function get()
    {
        $t = $this->createDataTable(User::Find());
        $t->cardStyle();
        //$t->paging = false;
        //$t->searching = false;

        $t->add("Username", "username");
        $t->add("test", function () {

            return "<input type='text' class='form-control form-control-sm' />";
        });
        $this->write($t);
    }
}
