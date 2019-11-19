<?

namespace App;

class User extends Core\User
{
    public function canRead()
    {
        return true;
    }
    public function canUpdate()
    {
        return true;
    }
    public function uri()
    { }
}
