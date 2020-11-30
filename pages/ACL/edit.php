<?php

use App\ACL;

class ACL_edit extends ALT\Page
{

    public function delACL()
    {
        $user_id = $_POST["user_id"];
        $usergroup_id = $_POST["usergroup_id"];
        $module = $_POST["module"];
        $value = $_POST["value"];
        $special_user = $_POST["special_user"];

        $acls = $this->getACL([
            "user_id" => $user_id,
            "usergroup_id" => $usergroup_id,
            "special_user" => $special_user,
            "module" => $module,
            "path" => $value["path"]
        ]);
        foreach ($acls as $acl) {
            $acl->delete();
        }

        return ["data" => true];
    }

    public function post()
    {
        $user_id = $_POST["user_id"];
        $usergroup_id = $_POST["usergroup_id"];
        $module = $_POST["module"];
        $value = $_POST["value"];
        $special_user = $_POST["special_user"];
        if ($value["action"]) {
            $this->getACL([
                "user_id" => $user_id,
                "usergroup_id" => $usergroup_id,
                "special_user" => $special_user,
                "module" => $module,
                "action" => $value["action"]
            ])->delete();

            if ($value["allow"]) {
                $o = new ACL();
                $o->module = $module;
                $o->action = $value["action"];
                $o->user_id = $user_id;
                $o->usergroup_id = $usergroup_id;
                $o->special_user = $special_user;
                $o->value = "allow";
                $o->save();
            }

            if ($value["deny"]) {
                $o = new ACL();
                $o->module = $module;
                $o->action = $value["action"];
                $o->user_id = $user_id;
                $o->usergroup_id = $usergroup_id;
                $o->special_user = $special_user;
                $o->value = "deny";
                $o->save();
            }
        } else {
            $acls = $this->getACL([
                "user_id" => $user_id,
                "usergroup_id" => $usergroup_id,
                "special_user" => $special_user,
                "module" => $module,
                "path" => $value["path"]
            ])->delete();


            if ($value["allow"]) {
                $o = new ACL();
                $o->module = $module;
                $o->path = $value["path"];
                $o->user_id = $user_id;
                $o->usergroup_id = $usergroup_id;
                $o->special_user = $special_user;
                $o->value = "allow";
                $o->save();
            }

            if ($value["deny"]) {
                $o = new ACL();
                $o->module = $module;
                $o->path = $value["path"];
                $o->user_id = $user_id;
                $o->usergroup_id = $usergroup_id;
                $o->special_user = $special_user;
                $o->value = "deny";
                $o->save();
            }
        }

        return ["data" => true];
    }

    public function data()
    {

        $ms = $this->app->modules();
        usort($ms, function ($a, $b) {
            return $a->class > $b->class;
        });

        return [
            "module" => $ms,
            "usergroup" => App\UserGroup::Find(),
            "user" => App\User::Find(),
            "special_user" => App\ACL::SPECIAL_USER
        ];
    }


    public function getValue($module = null, $usergroup_id = null, $user_id = null, $special_user = null)
    {
        if (!$usergroup_id && !$user_id && !$special_user) {
            return [];
        }

        $m = $this->app->module($module);

        if ($m) {
            $ds = [];
            foreach (ACL::ACTION as $action) {
                $d = [];
                $d["action"] = $action;

                $count = $this->getACL([
                    "user_id" => $user_id,
                    "usergroup_id" => $usergroup_id ?? null,
                    "special_user" => $special_user ?? null,
                    "module" => $module,
                    "action" => $action,
                    "value" => "allow"
                ])->count();

                $d["allow"] = $count ? true : false;


                $count = $this->getACL([
                    "user_id" => $user_id,
                    "usergroup_id" => $usergroup_id,
                    "special_user" => $special_user,
                    "module" => $module,
                    "action" => $action,
                    "value" => "deny"
                ])->count();
                $d["deny"] = $count ? true : false;
                $ds[] = $d;
            }
            $dds["action"] = $ds;
        }


        if ($special_user) {
            return $dds;
        }

        ///------------
        $paths = [];
        if ($m) {

            $paths[] = "";
            foreach ($m->getAction() as $act) {
                $paths[] = $act["filename"];
            }

            $ds = [];
            foreach ($paths as $path) {
                $d = [];
                $d["path"] = $path;


                $count = $this->getACL([
                    "user_id" => $user_id,
                    "usergroup_id" => $usergroup_id,
                    "special_user" => $special_user,
                    "module" => $module,
                    "path" => $path,
                    "value" => 'allow'
                ])->count();
                $d["allow"] = $count ? true : false;

                $count = $this->getACL([
                    "user_id" => $user_id,
                    "usergroup_id" => $usergroup_id,
                    "special_user" => $special_user,
                    "module" => $module,
                    "path" => $path,
                    "value" => 'deny'
                ])->count();
                $d["deny"] = $count ? true : false;

                $ds[] = $d;
            }
            $dds["path"] = $ds;
        }

        $acls = $this->getACL([
            "user_id" => $user_id,
            "usergroup_id" => $usergroup_id,
            "special_user" => $special_user,
            "module" => $module->name ?? null,
        ]);


        //custom
        $ds = [];
        foreach ($acls as $acl) {
            if (!$acl->path) continue;
            if (in_array($acl->path, $paths)) continue;
            $path = $acl->path;
            $d = [];
            $d["path"] = $path;

            $count = $this->getACL([
                "user_id" => $user_id,
                "usergroup_id" => $usergroup_id,
                "special_user" => $special_user,
                "module" => $module->name,
                "path" => $path,
                "value" => 'allow'
            ])->count();
            $d["allow"] = $count ? true : false;

            $count = $this->getACL([
                "user_id" => $user_id,
                "usergroup_id" => $usergroup_id,
                "special_user" => $special_user,
                "module" => $module->name,
                "path" => $path,
                "value" => 'deny'
            ])->count();



            $d["deny"] = $count ? true : false;

            $ds[] = $d;
        }
        $dds["custom"] = $ds;


        return $dds;
    }
    public function test()
    {
        outp($this->app->modules());
    }

    public function getACL(array $s)
    {
        $filter = $s;
        if (!$filter["user_id"]) unset($filter["user_id"]);
        if (!$filter["usergroup_id"]) unset($filter["usergroup_id"]);
        if (!$filter["special_user"]) unset($filter["special_user"]);

        if ($s["action"]) {
            $filter["action"] = $s["action"];
        } else {
            $filter["path"] = $s["path"];
        }
        if (!$filter["value"]) unset($filter["value"]);
        return ACL::Query($filter);
    }
}
