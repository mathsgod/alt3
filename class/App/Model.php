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

    public function save()
    {
        $key = $this->_key();

        if (!$this->$key) { //insert
            if (property_exists($this, "created_by")) {
                $this->created_by = self::$_app->user_id;
            }

            if (property_exists($this, "creator_group")) {
                $this->creator_group = self::$_app->usergroup_id;
            }
        } else {
            if (property_exists($this, "updated_by")) {
                $this->updated_by = self::$_app->user_id;
            }
        }
        return parent::save();
    }
}
