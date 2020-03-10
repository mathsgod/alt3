<?

namespace App;

abstract class Model extends Core\Model
{
    use ModelTrait;
    public function id()
    {
        $key = $this->_key();
        return $this->$key;
    }

    public static function _sv(string $name)
    {
        return self::$_app->sv($name);
    }

}
