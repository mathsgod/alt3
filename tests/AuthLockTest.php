<?
declare (strict_types = 1);
error_reporting(E_ALL && ~E_WARNING);
use PHPUnit\Framework\TestCase;

final class AuthLockTest extends TestCase
{
    public function testLogin()
    {
        $loader = new Composer\Autoload\ClassLoader();
        $app = new App\App(__DIR__, $loader);
        $w[] = "value>=3";
        $w[] = "date_add(time,Interval 180 second) > now()";
        $this->assertNull(App\AuthLock::First($w));
    }
}