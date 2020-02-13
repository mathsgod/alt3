<?

namespace App;

trait ModelTrait
{
    public static $_app;

    public function createdBy()
    {
        if ($this->created_by) {
            return new User($this->created_by);
        }
    }

    public function updatedBy()
    {
        if ($this->updated_by) {
            return new User($this->updated_by);
        }
    }

    private function _acl_allow($action = []): bool
    {

        $class = get_class($this);

        //--- deny ---
        if (array_intersect($action, self::$_app->acl["action"]["deny"][$class] ?? [])) {
            return false;
        }

        //creator owner
        if (array_intersect($action, self::$_app->acl["special_user"][1]["deny"][$class] ?? [])) {
            if (self::$_app->user_id == $this->created_by) {
                return false;
            }
        }

        //creator group
        if (array_intersect($action, self::$_app->acl["special_user"][2]["deny"][$class] ?? [])) {
            if (array_intersect(self::$_app->usergroup_id, $this->creator_group ?? [])) {
                return false;
            }
        }

        //everyone
        if (array_intersect($action,  self::$_app->acl["special_user"][3]["deny"][$class] ?? [])) {
            return false;
        }

        if (array_intersect($action, self::$_app->acl["action"]["allow"][$class] ?? [])) {
            return true;
        }

        //creator owner
        if (array_intersect($action, self::$_app->acl["special_user"][1]["allow"][$class] ?? [])) {
            if (self::$_app->user_id == $this->created_by) {
                return true;
            }
        }

        //creator group
        if (array_intersect($action, self::$_app->acl["special_user"][1]["allow"][$class] ?? [])) {
            if (array_intersect(self::$_app->usergroup_id, $this->creator_group ?? [])) {
                return true;
            }
        }

        //everyone
        if (array_intersect($action,  self::$_app->acl["special_user"][3]["allow"][$class] ?? [])) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canRead()
    {
        if (self::$_app->user->isAdmin()) {
            return true;
        }

        return  $this->_acl_allow(["FC", "R"]);
    }

    /**
     * @return bool
     */
    public function canUpdate()
    {
        if (self::$_app->user->isAdmin()) {
            return true;
        }

        return  $this->_acl_allow(["FC", "U"]);
    }

    /**
     * @return bool
     */
    public function canDelete()
    {

        if (self::$_app->user->isAdmin()) {
            return true;
        }
        return  $this->_acl_allow(["FC", "D"]);
    }

    public function id()
    {
        $key = $this->_key();
        return $this->$key;
    }


    /**
     * @return string
     */
    public function uri($a = null)
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
