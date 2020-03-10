<?

declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use App\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{

    public function testAdmin()
    {
        $loader = new Composer\Autoload\ClassLoader();
        $app = new App\App(__DIR__, $loader);
        $app->loginWith(User::_("admin"));

        $user = User::_("raymond");
        $this->assertTrue($user->canUpdate());
        $this->assertTrue($user->canDelete());

        $user = User::_("power");
        $this->assertTrue($user->canUpdate());
        $this->assertTrue($user->canDelete());
    }

    public function testUser()
    {
        $loader = new Composer\Autoload\ClassLoader();
        $app = new App\App(__DIR__, $loader);
        $app->loginWith(User::_("raymond"));

        $user = User::_("admin");
        $this->assertFalse($user->canUpdate());
        $this->assertFalse($user->canDelete());

        $user = User::_("power");
        $this->assertFalse($user->canUpdate());
        $this->assertFalse($user->canDelete());

        $user = User::_("raymond");
        $this->assertTrue($user->canUpdate());
        $this->assertFalse($user->canDelete());
    }


    public function testPowerUser()
    {

        $loader = new Composer\Autoload\ClassLoader();
        $app = new App\App(__DIR__, $loader);
        $app->loginWith(User::_("power"));

        $user = User::_("raymond");
        $this->assertTrue($user->canUpdate());
        $this->assertTrue($user->canDelete());


        $user = User::_("admin");
        $this->assertFalse($user->canUpdate());
        $this->assertFalse($user->canDelete());

        $user = User::_("power");
        $this->assertTrue($user->canUpdate());
        $this->assertFalse($user->canDelete());
    }

    public function testLogin()
    {
        $loader = new Composer\Autoload\ClassLoader();
        $app = new App\App(__DIR__, $loader);
        $this->assertTrue((bool) $app->login("raymond", "111111"));

        $this->expectException(\Exception::class);
        $app->login("admin", "abcdefg");
    }

    public function test_()
    {
        $admin = App\User::_("admin");
        $this->assertInstanceOf(App\User::class, $admin);

        $not_found = App\User::_("wre");
        $this->assertNull($not_found);
    }


    public function testIs()
    {
        $admin = App\User::_("admin");
        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($admin->is("Administrators"));
        //$this->assertFalse($admin->isUser());

        //$this->assertTrue($admin->isOneOf(["Guests", "Administrators"]));
    }
}
