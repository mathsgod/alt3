<?php

namespace GraphQL;

class Builder
{
    public $name;
    public $selectors = [];
    public function __construct(String $name, $args = null)
    {
        $this->name = $name;
        $this->args = $args;
    }


    public static function Query()
    {
    }

    public function add($selector)
    {
        $this->selectors[] = $selector;
        return $this;
    }


    public static function Mutation(string $name, array $args = [])
    {
        $builder = new self("mutation");

        $b = new Builder($name, $args);

        $builder->add($b);

        return $builder;
    }

    public static function Subscription(string $name, array $args = [])
    {
        $builder = new self("subscription");

        $b = new Builder($name, $args);

        $builder->add($b);

        return $builder;
    }

    private function toObject()
    {
        $obj = [];
        $obj[$this->name] = [];
        $obj[$this->name]["__args"] = $this->args;

        foreach ($this->selectors as $s) {

            $o = $s->toObject();
            foreach ($o as $k => $v) {
                $obj[$this->name][$k] = $v;
            }
        }
        return $obj;
    }

    public function __toString()
    {
        $obj = $this->toObject();

        return Utils::ObjToQuery($obj);
    }
}
