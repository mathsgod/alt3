<?php
namespace Type;

class UserGroup
{
    public function addUser(\App\UserGroup $root, $args, \App\App $context)
    {
        $user = $context->user;
        if (!$user->isPowerUser() && !$user->isAdmin()) {
            return false; //only power user and admin can update
        }

        if ($root->usergroup_id == 1) { //admin group
            if (!$context->user->isAdmin()) {  //only admin group can update admin group
                return false;
            }
        }


        $user = new \App\User($args["user_id"]);
        if (!$root->hasUser($user)) {
            $root->addUser($user);
        }
        return true;
    }

    public function removeUser(\App\UserGroup $root, $args, $context)
    {
        $user = $context->user;
        if (!$user->isPowerUser() && !$user->isAdmin()) {
            return false; //only power user and admin can update
        }

        if ($root->usergroup_id == 1) { //admin group
            if (!$context->user->isAdmin()) {  //only admin group can update admin group
                return false;
            }
        }

        $user = new \App\User($args["user_id"]);

        if ($root->hasUser($user)) {
            $root->removeUser($user);
        }
        return true;
    }
}
