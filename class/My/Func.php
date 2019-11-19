<?php
namespace My;

use Exception;

class Func
{
    private $function;
    private $parameter;
    public $id;

    public function name()
    {
        return $this->function;
    }

    public static function _($function, $parameters = null)
    {
        return new Func($function, $parameters);
    }

    public function __construct($function, $parameters = null)
    {
        $this->parameter = $parameters;
        $this->function = $function;
    }

    public function call($obj, $n = null)
    {
        if (is_null($this->function)) return $obj;
        if ($this->function == "") return "";
        $func = $this->function;

        if ($func instanceof \Closure || is_array($func)) {
            $parameter = [];
            $parameter[] = $obj;
            $parameter[] = $n;
            foreach ($this->parameter as $p) {
                $parameter[] = $p;
            }
            try {
                return call_user_func_array($func, $parameter);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        if (is_object($obj)) {
            if (in_array($func, array_keys(get_object_vars($obj)))) {
                return $obj->$func;
            } elseif (function_exists($func)) {
                return $func($obj);
            } else {
                try {
                    $v = "";
                    eval('$v=$obj->' . $func . ";");
                    return $v;
                } catch (Exception $e) {
                    return  $e->getMessage();
                }
            }
        } elseif (is_array($obj)) {
            if (array_key_exists($func,$obj)) {
                return $obj[$func];
            } elseif (function_exists($func)) {
                return $func($obj);
            }
            return "";
        }else{
            if (function_exists($func)) {
                return $func($obj);
            }
        }

        return $func;
    }
}
