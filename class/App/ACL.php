<?php

namespace App;

class ACL extends Model
{
    const ACTION = ["FC", "C", "R", "U", "D"];

    public static $_ACTION = ["FC", "C", "R", "U", "D"];
    public static $_SPECIAL_USER = array(1 => "CREATOR OWNER", 2 => "CREATOR GROUP", 3 => "EVERYONE");
    public static $_Type = ["Normal", "Regexp"];

    public function SpecialUser()
    {
        return self::$_SPECIAL_USER[$this->special_user];
    }

    public function path()
    {
        if ($this->path == "" && !$this->action) {
            return "[$this->module]";
        }
        return $this->path;
    }

    public function UserName()
    {
        if ($this->user_id) {
            return $this->User();
        } elseif ($this->usergroup_id) {
            return $this->UserGroup()->name;
        } else {
            return self::$_SPECIAL_USER[$this->special_user];
        }
    }

    public static $_ini = null;
    public static function INI()
    {
        if (is_null(self::$_ini)) {
            self::$_ini = parse_ini_file(SYSTEM . "/acl.ini", true);
        }

        return self::$_ini;
    }

    public static function SettingIni($path)
    {
        $app = App::_();
        $path = parse_url($path, PHP_URL_PATH);
        //system path
        $p = explode("/", $path);

        while (count($p)) {
            $path = implode("/", $p);
            $file = $app->getFile("pages/" . $path . "/setting.ini");
            if (file_exists($file)) {
                break;
            }
            array_pop($p);
        }

        if ($file) {
            $ini = parse_ini_file($file, true);
            return $ini["acl"];
        }

        return null;
    }

    private static $_CACHE = [];
    public static function Allow($path, $action = null, $user = null, $debug = false)
    {
        $raw_path = $path;
        $p = parse_url($path);
        $path = $p["path"];

        $ps = explode("/", $path);
        $path = implode("/", array_filter($ps, function ($p) {
            return !is_numeric($p);
        }));

        $ps = explode("/", $path);

        if (is_null($user)) {
            $user = self::$_app->user;
        } elseif (!($user instanceof User)) {
            $user = new User($user);
        }

        $result = $user->isAdmin();

        if (!$result) {
            if ($user->isUser()) {
                $result = self::$_app->config["system"]["user_default_acl"];

                //if module is system, set false
                $module = Module::ByPath($path);
                if (startsWith($module->class, "App")) {
                    $result = false;
                }
            }
        }

        $ugs = $user->UserGroup();

        /*
        if (!$result) {
            if ($module = Module::ByPath($path)) {
                if ($acl = $module->acl) {
                    foreach ($acl as $pattern => $usergroup) {
                        if (fnmatch($pattern, $path)) {
                            $ugs = $usergroup;
                            break;
                        }
                    }
                    $usergroups = (array)$user->UserGroup()->map(function ($ug) {
                        return $ug->name;
                    });

                    $inters = Set::Create($usergroups)->intersection($ugs);

                    if ($inters->count()) {
                        $result = true;
                    }
                }
            }
        }*/

        $ini = ACL::INI();
        if (!$result) {
            if ($action != null) {
                $groups = explode(",", trim($ini[$action][$path]));
            } elseif ($ini["Path"][$path] != "") {
                $groups = explode(",", trim($ini["Path"][$path]));
            }

            if ($groups) {
                foreach ($ugs as $ug) {
                    if (in_array($ug->name, $groups)) {
                        $result = true;
                        break;
                    }
                }
            }
        }

        $module = array_shift($ps);

        if ($groups = explode(",", $ini["FC"][$module])) {
            foreach ($ugs as $ug) {
                if (in_array($ug->name, $groups)) {
                    $result = true;
                    break;
                }
            }
        }

        //load all acl
        $w = [];
        $u[] = "user_id=" . $user->user_id;
        foreach ($ugs as $ug) {
            $u[] = "usergroup_id=$ug->usergroup_id";
        }
        if (!$user->isGuest()) {
            $u[] = "special_user=3";
        }
        $w[] = implode(" or ", $u);

        if (!isset(self::$_CACHE[$user->user_id])) {
            self::$_CACHE[$user->user_id] = (array) self::Find($w);
        }

        foreach (self::$_CACHE[$user->user_id] as $acl) {
            if ($acl->module == $module) {
                if ($acl->path == $ps[0]) {
                    $v = $acl->value();
                    if ($v == "deny") {
                        return false;
                    }
                    if ($v == "allow") {
                        $result = true;
                    }
                }

                if ($acl->action == "FC") {
                    $v = $acl->value();
                    if ($v == "deny") {
                        return false;
                    }
                    if ($v == "allow") {
                        $result = true;
                    }
                }

                if ($action !== null) {
                    if ($acl->action == $action) {
                        $v = $acl->value();
                        if ($v == "deny") {
                            return false;
                        }
                        if ($v == "allow") {
                            $result = true;
                        }
                    }
                }
            }
        }

        return $result;
    }
    // special user
    // 1=creator owner,2=creator group,3=everyone
    public static function Allow2($action, $module, $object_id = null, $field = null, $user = null)
    {
        if (API::$system_mode) {
            return true;
        }
        // API::output(func_get_args());
        if ($action == "index" || $action == "list") {
            $action = "L";
        }

        if (is_null($user)) {
            $user = API::User();
        } elseif (!($user instanceof User)) {
            $user = new User($user);
        }

        if (is_object($object_id)) {
            $object_id = $object_id->id();
        }
        if ($action == "ae") {
            if ($object_id) {
                $action = "U";
            } else {
                $action = "C";
            }
        }

        $result = $user->isAdmin();
        // get module_id
        if (is_string($module)) {
            $module = Module::_($module);
        }
        // get creator owner
        $ini = ACL::INI();
        $groups_str = $ini["FC"][$module->name];
        if ($groups_str != "") {
            $groups = explode(",", trim($groups_str));
            foreach ($user->UserGroup() as $ug) {
                if (in_array($ug->name, $groups)) {
                    $result = true;
                    break;
                }
            }
        }

        if (!$result) {
            if (in_array($action, array("L", "C", "R", "U", "D"))) {
                $groups_str = $ini[$action][$module->name];
            } else {
                $groups_str = $ini["ACTION"][$module->name . "/" . $action];
            }

            if ($groups_str != "") {
                $groups = explode(",", trim($groups_str));
                foreach ($user->UserGroup() as $ug) {
                    if (in_array($ug->name, $groups)) {
                        $result = true;
                        break;
                    }
                }
            }
        }

        if (!is_null($object_id) && $module->module_id) {
            if (in_array("owner", $module->fields())) {
                $o = $module->FactoryObject($object_id);
                $w = array();
                $w[] = "module_id=" . $module->module_id;
                $w[] = "object_id=" . $object_id . " OR object_id is null";
                $w[] = "(action=" . API::DB()->quote($action) . " || action='FC')";
                $w[] = "special_user=2";

                foreach (ACL::find($w) as $acl) {
                    $v = $acl->value();
                    if ($v == "deny") {
                        return false;
                    }
                    if ($v == "allow") {
                        foreach ($o->Owner()->UserGroup() as $ug) {
                            if ($user->IsGroupOf($ug)) {
                                $result = true;
                            }
                        }
                    }
                }

                $w = array();
                $w[] = "module_id=" . $module->module_id;
                $w[] = "object_id=" . $object_id . " OR object_id is null";
                $w[] = "(action=" . API::DB()->quote($action) . " || action='FC')";
                $w[] = "special_user=1";

                foreach (ACL::find($w) as $acl) {
                    $v = $acl->value();
                    if ($v == "deny") {
                        return false;
                    }
                    if ($v == "allow") {
                        if ($o->owner == $user->user_id) {
                            $result = true;
                        }
                    }
                }
            }
        }

        if ($module->module_id) {
            $w = array();
            $w[] = "module_id=" . $module->module_id;
            $w[] = (!$object_id) ? "object_id is null" : "object_id=" . $object_id . " OR object_id is null";
            $w[] = is_null($field) ? "field is null" : "field=" . API::DB()->quote($field) . " OR field is null";
            $w[] = "action='FC' OR action=" . API::DB()->quote($action) . "";

            $u = array();
            // check everyone
            if (!$user->IsGuest()) { // guest account not belong to everyone
                $u[] = "special_user=3";
            }

            $usergroup_ids = array();

            foreach ($user->UserGroup() as $ug) {
                $u[] = "usergroup_id=" . $ug->usergroup_id;
            }

            $u[] = "user_id=" . $user->user_id;

            $w[] = implode(" OR ", $u);

            foreach (ACL::find($w) as $acl) {
                $v = $acl->value();
                if ($v == "deny") {
                    return false;
                }
                if ($v == "allow") {
                    $result = true;
                }
            }
        }

        return $result;
    }

    public function Value()
    {
        if ($this->code == "") {
            return $this->value;
        }
        // API::output($this);
        $func = "_ACL_func_" . $this->acl_id;
        $eval = <<<EOT
function {$func}(){
    ?>{$this->code}<?
}
EOT;
        eval($eval);

        if ($func()) {
            return $this->value;
        }
    }
}
