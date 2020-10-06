<?php
// Created By: Raymond Chong
// Last Updated:
use App\Config;
use App\User;
use App\UserGroup;
use App\UserList;

class User_ae extends ALT\Page
{

    public function get()
    {
        $obj = $this->object();
        $obj->password = "";
        $card = $this->createCard();
        $form = $card->addForm($obj);


        $user = $this->app->user;
        if ($user->isAdmin() || $user->isPowerUser() || !$obj->user_id) {
            $form->add("Username")->input("username")->required();
            $form->add("Password")->password("password")->required();
        }

        $form->add("First name")->input("first_name")->required();
        $form->add("Last name")->input("last_name");
        $form->add("Phone")->input("phone");
        $form->add("Email")->email("email")->required();

        $form->add("Address")->input("addr1");
        $form->add("")->input("addr2");
        $form->add("")->input("addr3");

        $form->add("Join date")->datePicker("join_date")->required();
        $form->add("Status")->select("status")->required()->option(User::STATUS);
        $form->add("Expiry date")->datePicker("expiry_date");


        if (($user->isAdmin() || $user->isPowerUser()) && !$obj->user_id) {
            $u = UserGroup::_("Users");
            $ugs = UserGroup::find()->filter(function ($o) {
                if ($o->name == "Administrators" && !$this->app->user->isAdmin()) return false;
                return true;
            });
            $form->add("User group")->select("usergroup_id")->multiple()->option($ugs, "name", "usergroup_id");
        }


        $form->add("Language")->select("language")->option($this->app->config["system"]["language"]);
        $form->add("Default page")->input("default_page");


        $this->write($card);
    }
}
