<?php

if (!function_exists("js")) {
    function js(string $code)
    {
        return new \JS\Code($code);
    }
}

if (!function_exists("js_object_encode")) {
    function js_object_encode($val)
    {
        if ($val instanceof JS\Code) {
            return $val->code;
        }
        if (is_string($val)) return '"' . addslashes($val) . '"';
        if (is_numeric($val)) return $val;
        if ($val === null) return 'null';
        if ($val === true) return 'true';
        if ($val === false) return 'false';

        $assoc = false;
        $i = 0;
        foreach ($val as $k => $v) {
            if ($k !== $i++) {
                $assoc = true;
                break;
            }
        }
        $res = array();
        foreach ($val as $k => $v) {
            $v = js_object_encode($v);
            if ($assoc) {
                $k = '"' . addslashes($k) . '"';
                $v = $k . ':' . $v;
            }
            $res[] = $v;
        }
        $res = implode(',', $res);
        return ($assoc) ? '{' . $res . '}' : '[' . $res . ']';
    }
}
