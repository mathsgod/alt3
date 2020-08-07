<?php

namespace Vue;

class JSObject
{
}

// alternative json_encode
function _json_encode($val)
{
    if ($val instanceof JSCode) return $val->code;
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
        $v = _json_encode($v);
        if ($assoc) {
            $k = '"' . addslashes($k) . '"';
            $v = $k . ':' . $v;
        }
        $res[] = $v;
    }
    $res = implode(',', $res);
    return ($assoc) ? '{' . $res . '}' : '[' . $res . ']';
}
