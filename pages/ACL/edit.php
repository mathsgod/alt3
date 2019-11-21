<?

use App\ACL;
use App\Module;

class ACL_edit extends ALT\Page
{

    public function delACL()
    {
        $user_id = $_POST["user_id"];
        $usergroup_id = $_POST["usergroup_id"];
        $module = $_POST["module"];
        $value = $_POST["value"];

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

        return ["code" => 200];
    }

    public function post()
    {
        $user_id = $_POST["user_id"];
        $usergroup_id = $_POST["usergroup_id"];
        $module = $_POST["module"];
        $value = $_POST["value"];

        if ($value["path"]) {
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

        if ($value["action"]) {
            $acls = $this->getACL([
                "user_id" => $user_id,
                "usergroup_id" => $usergroup_id,
                "special_user" => $special_user,
                "module" => $module,
                "action" => $value["action"]
            ]);

            foreach ($acls as $acl) {
                $acl->delete();
            }

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
        }

        return ["code" => 200];
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
            "user" => App\User::Find()
        ];
    }

    public function getValue($module, $usergroup_id, $user_id)
    {

        $module = $this->app->module($module);

        if (!$usergroup_id && !$user_id) {
            return [];
        }

        if ($module) {
            $ds = [];
            foreach (ACL::ACTION as $action) {
                $d = [];
                $d["action"] = $action;

                $count = $this->getACL([
                    "user_id" => $user_id,
                    "usergroup_id" => $usergroup_id,
                    "special_user" => $special_user,
                    "module" => $module->name,
                    "action" => $action,
                    "value" => "allow"
                ])->count();

                $d["allow"] = $count ? 1 : 0;

                $w = [];
                if ($user_id) {
                    $w[] = "user_id=$user_id";
                } elseif ($usergroup_id) {
                    $w[] = "usergroup_id=$usergroup_id";
                } elseif ($special_user) {
                    $w[] = "special_user=$special_user";
                }
                $w[] = ["module=?", $module->name];
                $w[] = ["action=?", $action];
                $w[] = "value='deny'";
                $d["deny"] = ACL::count($w) ? 1 : 0;
                $ds[] = $d;
            }
            $dds["action"] = $ds;
        }

        ///------------
        $paths = [];
        if ($module) {
            $ds = [];

            foreach ($module->getAction() as $act) {
                $paths[] = $act["filename"];
            }

            foreach ($paths as $path) {
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
                $d["allow"] = $count ? 1 : 0;

                $count = $this->getACL([
                    "user_id" => $user_id,
                    "usergroup_id" => $usergroup_id,
                    "special_user" => $special_user,
                    "module" => $module->name,
                    "path" => $path,
                    "value" => 'deny'
                ])->count();
                $d["deny"] = $count ? 1 : 0;

                $ds[] = $d;
            }
            $dds["path"] = $ds;
        }

        $acls = $this->getACL([
            "user_id" => $user_id,
            "usergroup_id" => $usergroup_id,
            "special_user" => $special_user,
            "module" => $module ? $module->name : null,
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
            $d["allow"] = $count ? 1 : 0;

            $count = $this->getACL([
                "user_id" => $user_id,
                "usergroup_id" => $usergroup_id,
                "special_user" => $special_user,
                "module" => $module->name,
                "path" => $path,
                "value" => 'deny'
            ])->count();



            $d["deny"] = $count ? 1 : 0;

            $ds[] = $d;
        }
        $dds["custom"] = $ds;





        return $dds;
    }

    public function getACL($s)
    {
        $module = $s["module"];
        $user_id = $s["user_id"];
        $usergroup_id = $s["usergroup_id"];
        $special_user = $s["special_user"];
        $path = $s["path"];
        $action = $s["action"];
        $value = $s["value"];

        $w = [];
        $w[] = $module ? ["module=?", $module] : "module is null";

        if ($user_id) {
            $w[] = ["user_id=?", $user_id];
        } elseif ($usergroup_id) {
            $w[] = ["usergroup_id=?", $usergroup_id];
        } elseif ($special_user) {
            $w[] = ["special_user=?", $special_user];
        }

        if ($path) {
            $w[] = ["path=?", $path];
        }

        if ($action) {
            $w[] = ["action=?", $action];
        }

        if ($value) {
            $w[] = ["value=?", $value];
        }


        return ACL::Find($w);
    }
}
