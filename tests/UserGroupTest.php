<?php
declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use App\User;
use App\UserGroup;
use PHPUnit\Framework\TestCase;

final class UserGroupTest extends TestCase
{
    public function test_()
    {

        $ug = UserGroup::_("Users");


        $this->assertInstanceOf(UserGroup::class, $ug);


        $user = new User();
        $user->username = "test1";
        $user->first_name = "test user1";
        $user->join_date = date("Y-m-d");
        $user->save();

        $ug->addUser($user);


        $this->assertTrue($ug->hasUser($user));

        $ug->removeUser($user);
        $this->assertFalse($ug->hasUser($user));

        $user->delete();
    }
}
