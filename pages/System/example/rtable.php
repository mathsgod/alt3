<?php

class System_example_rtable extends ALT\Page
{



    public function ds($rt)
    {
        $rt->source = App\User::Query();
        $rt->add("usergroup_id", function ($obj) {
            $ugs = [];
            foreach ($obj->UserGroup() as $ug) {
                $ugs[] = (string) $ug;
            }
            return implode(",", $ugs);
        })->searchCallback(function ($v) {
            $usergroup_id = implode(",", $v);
            return ["user_id in (select user_id from UserList where usergroup_id in ($usergroup_id))"];
        });
        return $rt;
    }
}
