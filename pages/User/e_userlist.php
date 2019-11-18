<?php
use App\UserGroup;
use App\UserList;

class User_e_userlist extends ALT\Page
{
    public function post()
    {
        $obj = $this->object();
        $ids = [];
        foreach ($obj->UserList() as $ul) {
            $ids[] = $ul->usergroup_id;
            if (!in_array($ul->usergroup_id, $_POST["usergroup_id"])) {
                $ul->delete();
            }
        }

        foreach ($_POST["usergroup_id"] as $usergroup_id) {
            if (in_array($usergroup_id, $ids)) continue;
            $o = new UserList();
            $o->usergroup_id = $usergroup_id;
            $o->user_id = $obj->user_id;
            $o->save();
        }
        $this->app->alert->success("User updated");
        $this->_redirect($obj->uri("v"));
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