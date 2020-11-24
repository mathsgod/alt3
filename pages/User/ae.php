<?php
// Created By: Raymond Chong
// Last Updated:
use App\Config;
use App\User;
use App\UserGroup;
use App\UserList;
use GraphQL\Builder;

class User_ae extends ALT\Page
{
    public function post()
    {
        $data = $_POST;
        $data["status"] = intval($data["status"]);

        $obj = $this->object();
        if ($obj->user_id) {

            $resp = $this->app->executeQuery(Builder::Mutation("updateUser", [
                "user_id" => $obj->user_id,
                "data" => $data
            ]), true);
        } else {
            $data["usergroup_id"] = array_map(function ($ug_id) {
                return intval($ug_id);
            }, $data["usergroup_id"]);
            $resp = $this->app->executeQuery(Builder::Subscription("createUser", $data), true);
        }


        if ($resp["error"]) {
            $this->alert->danger($resp["error"]["message"]);
            $this->redirect();
            return;
        }

        if ($obj->user_id) {
            $user_id = $resp["data"]["updateUser"];
            $this->alert->info("User updated");
            $this->redirect("User/$obj->user_id/v");
        } else {
            $user_id = $resp["data"]["createUser"];
            $this->alert->info("User created");
            $this->redirect("User/$user_id/v");
        }
    }

    public function get()
    {


        $obj = $this->object();
        $obj->password = "";
        $form = $this->createRForm($obj);

        $user = $this->app->user;
        if (!$obj->user_id) {
            $form->add("Username")->input("username")->required();
            $form->add("Password")->password("password")->required()->setAttribute("auto-complete", "new-password");
        }

       
        $form->add("First name")->input("first_name")->required();
        $form->add("Last name")->input("last_name");
        $form->add("Phone")->input("phone");
        $form->add("Email")->email("email")->required();
        $form->add("Address")->input("addr1");
        $form->add("")->input("addr2");
        $form->add("")->input("addr3");

        $form->add("Join date")->date("join_date")->required();
        $form->add("Status")->select("status")->required()->option(User::STATUS);
        $form->add("Expiry date")->date("expiry_date");


        if (($user->isAdmin() || $user->isPowerUser()) && !$obj->user_id) {
            $u = UserGroup::_("Users");
            $ugs = UserGroup::Query()->toList()->filter(function ($o) {
                if ($o->name == "Administrators" && !$this->app->user->isAdmin()) return false;
                return true;
            });
            $form->add("User group")->multiselect("usergroup_id")->option($ugs, "name", "usergroup_id");
        }

        $form->add("Language")->select("language", $this->app->config["system"]["language"]);
        $form->add("Default page")->input("default_page");
        $this->write($form);
    }
}
