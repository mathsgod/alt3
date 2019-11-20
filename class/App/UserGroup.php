<?php

namespace App;

class UserGroup extends Core\UserGroup
{
    use ModelTrait;

    public function User()
    {
        return $this->UserList()->map(function ($o) {
            return $o->User();
        });
    }

    private static $_CACHE;
    public static function _($name)
    {
        if (isset(self::$_CACHE[$name])) {
            return self::$_CACHE[$name];
        }

        if ($g = UserGroup::first("name='$name' or code='$name'")) {
            self::$_CACHE[$name] = $g;
        } else {
            self::$_CACHE[$name] = null;
        }
        return self::$_CACHE[$name];
    }

    public function hasUser(User $user)
    {
        foreach ($user->UserList() as $ul) {
            if ($ul->usergroup_id == $this->usergroup_id) return true;
        }
        return false;
    }

    public function addUser(User $user)
    {
        $ul = new UserList();
        $ul->user_id = $user->user_id;
        $ul->usergroup_id = $this->usergroup_id;
        $ul->save();
        return $ul;
    }

    public function removeUser(User $user)
    {
        foreach ($user->UserList() as $ul) {
            if ($ul->usergroup_id == $this->usergroup_id) {
                $ul->delete();
            }
        }
        return;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function canUpdate()
    {
        if ($this->usergroup_id <= 4) return false;
        return parent::canUpdate();
    }

    public function canDelete()
    {
        if ($this->_Size("UserList")) return false;
        if ($this->usergroup_id <= 4) return false;
        return parent::canDelete();
    }
}
