<?php

use App\UserGroup;
use App\UserList;

class User_e_userlist extends ALT\Page
{
    public function post()
    {
        $obj = $this->object();

        $ugs = $obj->UserGroup()->toArray();


        foreach ($ugs as $ug) {
            if (!in_array($ug->usergroup_id, $_POST["usergroup_id"])) {
                //remove user
                $ug->removeUser($obj);
            }
        }


        foreach ($_POST["usergroup_id"] as $usergroup_id) {
            $ug = new UserGroup($usergroup_id);
            $ug->addUser($obj);
        }

        $this->app->alert->success("User updated");
        $this->redirect($obj->uri("v"));
    }

    public function get()
    {
        $obj = $this->object();

        $ug = $obj->UserList()->map(function ($o) {
            return $o->usergroup_id;
        })->asArray();

        $obj->usergroup_id = $ug;

        $mv = $this->createE($obj);
        $mv->add("Name", "__toString()");

        $ugs = UserGroup::find()->filter(function ($o) {
            if ($o->name == "Administrators" && !$this->app->user->isAdmin()) return false;
            return true;
        });

        $mv->add("User Group")->multiSelect2("usergroup_id")->ds($ugs);
        $this->write($this->createForm($mv));
    }
}
