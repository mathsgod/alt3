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
