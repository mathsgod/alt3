<?

declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
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
