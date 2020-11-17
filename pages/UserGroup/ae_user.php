<?php

use App\User;
use App\UserList;

class UserGroup_ae_user extends ALT\Page
{
    public function data()
    {
        $data = [];
        foreach (App\User::Find(null, "status,first_name") as $user) {
            if ($user->user_id == $this->app->user_id) continue;
            $d = [];
            $d["name"] = (string)$user;
            $d["user_id"] = $user->user_id;
            $d["usergroup_id"] = [];
            foreach ($user->UserList() as $ul) {
                $d["usergroup_id"][] = $ul->usergroup_id;
            }

            $data[] = $d;
        }

        $usergroups = [];
        if ($this->app->user->isAdmin()) {
            $usergroups = App\UserGroup::Query()->toArray();
        } else {
            foreach (App\UserGroup::Query() as $ug) {
                if ($ug->usergroup_id == 1) continue;
                $usergroups[] = $ug;
            }
        }


        return ["usergroups" => $usergroups, "uls" => $data];
    }
}
