<?php
namespace My;
class Query extends \P\Query {
    public $object;
    
    public function __toString(){
        return parent::__toString();
    }

    public function bind($object) {
        $this->binding($object);
        $this->object = $object;
        return $this;
    }

    public function __call($name, $args) {
        if ($name == "empty") {
            return parent::__call($name, $args);
        }
        parent::attr($name, $args[0]);
        return $this;
    }

    public function binding($object, $o_id) {
        if (is_null($object))return;
        $this->find("*")->each(function($i, $o)use($object, $o_id) {
                foreach($o->attributes as $k => $v) {
                    if ($k == "index")continue;
                    if ($k[0] == "_") {
                        $o->attributes[substr($k, 1)] = $v($object);
                        continue;
                    }

                    if ($v instanceof \Closure) {
                        $o->attributes["$k"] = $v($object, $o_id);
                        $o->attributes["_$k"] = $v;
                    }
                }
            }
            );
        $this->find("a")->each(function($i, $o)use($object) {
                $index = $o->attributes["index"];
                if (!$index)return;
                Query::_($o)->text(Func::_($index)->call($object));
            }
            );

        $this->find("input")->each(function($i, $o)use($object) {
                $index = $o->attributes["index"];
                if (!$index)return;
                if ($o->attributes["type"] == "password")return;
                if ($o->attributes["type"] == "checkbox") {
                    if ($o->attributes["value"] == Func::_($index)->call($object)) {
                        $o->attributes["checked"] = true;
                    } else {
                        $o->attributes["checked"] = false;
                    }
                } else {
                    $o->attributes["value"] = is_object($object)?$object->$index: $object[$index];
                }
            }
            );

        $this->find("select")->each(function($i, $o)use($object) {
                $index = $o->attributes["index"];
                if (!$index)return;
                $val = "";
                if (property_exists($object, $index)) {
                    $val = $object->$index;
                }

                if (Query::_($o)->hasClass("select2")) {
                    // add the value to option
                    $opt_vals = [];
                    foreach(Query::_($o)->find("option") as $opts) {
                        $opt_vals[] = $opts->attributes["value"];
                    }

                    $vals = explode(",", $val);
                    foreach($vals as $v) {
                        if ($v == "")continue;
                        if (!in_array($v, $opt_vals)) {
                            $option = p("option")->text($v)->val($v);
                            Query::_($o)->append($option);
                        }
                    }

                    if (!is_array($val)) {
                        $vals = explode(",", $val);
                    } else {
                        $vals = $val;
                    }

                    Query::_($o)->find("option")->each(function($j, $_)use($vals) {
                            if (in_array($_->attributes["value"], $vals, false)) {
                                $_->attributes["selected"] = true;
                            } else {
                                unset($_->attributes["selected"]);
                            }
                        }
                        );
                } elseif (Query::_($o)->hasClass("multiselect")) {
                    $vals = explode(",", $val);
                    Query::_($o)->find("option")->each(function($j, $_)use($vals) {
                            if (in_array($_->attributes["value"], $vals, true)) {
                                $_->attributes["selected"] = true;
                            } else {
                                unset($_->attributes["selected"]);
                            }
                        }
                        );
                } else {
                    Query::_($o)->find("option")->each(function($j, $o)use($val) {
                            if ($o->attributes["value"] == $val) {
                                $o->attributes["selected"] = true;
                            } else {
                                unset($o->attributes["selected"]);
                            }
                        }
                        );
                }
            }
            );
        $this->find("textarea")->each(function($i, $o)use($object) {
                $index = $o->attributes["index"];
                if (!$index)return;
                // if (!array_key_exists($index, get_object_vars($object)))return;
                $val = Func::_($index)->call($object);
                Query::_($o)->text($val);
            }
            );

        $this->find("img")->each(function($i, $o)use($object) {
                $index = $o->attributes["index"];
                if (!$index)return;
                // if (!array_key_exists($index, get_object_vars($object)))return;
                $val = Func::_($index)->call($object);
                p($o)->attr('src', $val);
            }
            );

        $this->find("td,div,p")->each(function($i, $o)use($object, $o_id) {
                $index = $o->attributes["index"];
                if (!$index)return;
                if ($index instanceof \Closure) {
                    $val = $index($object, $o_id);
                    Query::_($o)->html($val);
                } else {
                    $val = Func::_($index)->call($object);
                    Query::_($o)->text($val);
                }
            }
            );

        $this->find("td,div")->each(function($i, $o)use($object) {
                $index = $o->attributes["index"];
                if (!$index)return;
                $alink = $o->attributes["alink"];
                if (!$alink)return;
                $result_object = Func::_($index)->call($object);
                if (is_object($result_object)) {
                    $_alink = $result_object->uri($alink);
                    $a = p("a")->attr('href', $_alink);
                    $a->append(Query::_($o)->contents());
                    p($o)->append($a);
                }
            }
            );
        return $this;
    }
}

?>