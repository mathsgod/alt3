<?

namespace App;

trait ModelTrait
{
    public static $_app;
    public function canRead()
    {
        if (self::$_app->user->isAdmin()) {
            return true;
        }

        $deny = self::$_app->acl["action"]["deny"];
        if ($deny[get_class($this)]["FC"]) {
            return false;
        }
        if ($deny[get_class($this)]["R"]) {
            return false;
        }

        $allow = self::$_app->acl["action"]["allow"];
        if ($allow[get_class($this)]["FC"]) {
            return true;
        }
        if ($allow[get_class($this)]["R"]) {
            return true;
        }

        return false;
    }

    public function canUpdate()
    {
        if (self::$_app->user->isAdmin()) {
            return true;
        }

        $deny = self::$_app->acl["action"]["deny"];
        if ($deny[get_class($this)]["FC"]) {
            return false;
        }
        if ($deny[get_class($this)]["U"]) {
            return false;
        }

        $allow = self::$_app->acl["action"]["allow"];
        if ($allow[get_class($this)]["FC"]) {
            return true;
        }
        if ($allow[get_class($this)]["U"]) {
            return true;
        }

        return false;
    }

    public function canDelete()
    {
        if (self::$_app->user->isAdmin()) {
            return true;
        }

        $deny = self::$_app->acl["action"]["deny"];
        if ($deny[get_class($this)]["FC"]) {
            return false;
        }
        if ($deny[get_class($this)]["D"]) {
            return false;
        }

        $allow = self::$_app->acl["action"]["allow"];
        if ($allow[get_class($this)]["FC"]) {
            return true;
        }
        if ($allow[get_class($this)]["D"]) {
            return true;
        }

        return false;
    }

    public function id()
    {
        $key = $this->_key();
        return $this->$key;
    }


    public function uri($a = null): string
    {
        $reflect = new \ReflectionClass($this);
        $uri = $reflect->getShortName();
        if ($this->id()) {
            $uri .= "/" . $this->id();
        }
        if (isset($a)) {
            $uri .= "/" . $a;
        }
        return $uri;
    }

    public function __call($function, $args)
    {
        $class = get_class($this);

        //check const
        $c = new \ReflectionClass($class);
        if ($const = $c->getConstants()) {

            $decamlize = function ($string) {
                return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
            };
            $field = $decamlize($function);

            if (array_key_exists(strtoupper($field), $const)) {
                return $const[strtoupper($field)][$this->$field];
            }

            if (array_key_exists($function, $const)) {
                return $const[$function][$this->$field];
            }
        }
        return parent::__call($function, $args);
    }
}
