<?php

use App\User;

class User_index extends ALT\Page
{
    public function get()
    {
        $tab = $this->createTab();

        foreach (User::STATUS as $k => $v) {
            $tab->add($v, "list", $k);
        }
        //$tab->add("All user", "list", - 1)->addBadge("a")->addClass("bg-yellow");
        $tab->add("All user", "list", -1); //->addClass("bg-yellow");

        $tab->add("All user2", "list2", -1); //->addClass("bg-yellow");


        //        if ($this->app->user->isAdmin()) {
        //            $tab->add("Test DT", "list2");
        //            $tab->add("Test RT2", "list3");

        //      }

        //$tab->add("Test","list2");
        $this->write($tab);
    }
}
