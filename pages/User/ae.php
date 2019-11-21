<?php
// Created By: Raymond Chong
// Last Updated:
use App\Config;
use App\User;
use App\UserGroup;
use App\UserList;

class User_ae extends ALT\Page
{
    public function post()
    {
        parent::post();

        $obj = $this->object();
        if (isset($_POST["usergroup_id"])) {
            foreach ($_POST["usergroup_id"] as $usergroup_id) {
                $o = new UserList();
                $o->usergroup_id = $usergroup_id;
                $o->user_id = $obj->user_id;
                $o->save();
            }
        }
    }

    public function get()
    {
        $obj = $this->object();
        $mv = $this->createE();
        $user = $this->app->user;
        if ($user->isAdmin() || $user->isPowerUser() || !$obj->user_id) {
            $c = $mv->add("Username");
            $c->input("username")->required();
        }

        $password = $mv->add("Password")->password("password")->minlength($this->app->config["user"]["password-length"]);
        if (!$obj->user_id) {
            $password->required();
        }

        $mv->add("First name")->input("first_name")->required();
        $mv->add("Last name")->input("last_name");


        $mv->add("Phone")->input("phone");
        $mv->add("Email")->email("email")->required();

        $r = $mv->add("Address");
        $r->input("addr1");
        $r->input("addr2");
        $r->input("addr3");

        $r = $mv->add("Join date");
        if ($user->isAdmin() || $user->isPowerUser() || !$obj->user_id) {
            $r->date("join_date")->required(true);
        } else {
            $r->date("join_date");
        }

        if (!$obj->isAdmin()) {
            if (($user->isAdmin() || $user->isPowerUser()) && $user->user_id != $obj->user_id) {
                $mv->add("Status")->select("status")->ds(User::STATUS);
                $mv->add("Expiry date")->date("expiry_date");
            }
        }

        if (($user->isAdmin() || $user->isPowerUser()) && !$obj->user_id) {
            $mv->addHr();
            $u = UserGroup::_("Users");

            $ugs = UserGroup::find()->filter(function ($o) {
                if ($o->name == "Administrators" && !$this->app->user->isAdmin()) return false;
                return true;
            });

            $mv->add("User group")->multiSelect2("usergroup_id")->ds($ugs)->val([$u->usergroup_id]);
        }
        $mv->addHr();

        $mv->add("Language")->select("language")->ds($this->app->config["language"]);
        $mv->add("Default page")->input("default_page");
        $this->write($this->createForm($mv));
    }
}
