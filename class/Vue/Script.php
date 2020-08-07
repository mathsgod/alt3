<?php

namespace Vue;

class Script
{
    public $el = null;
    public $data = [];
    public $methods = [];

    public function merge(self $script)
    {
        $v = clone $this;

        foreach ($script->methods as $name => $method) {
            $v->methods[$name] = $method;
        }
        foreach ($script->data as $name => $value) {
            $v->data[$name] = $value;
        }

        return $v;
    }

    public function __toString()
    {
        $data = json_encode($this->data);


        $m = [];
        foreach ($this->methods as $name => $function) {
            $m[] = $name . ":" . $function;
        }

        $ms = implode(",", $m);

        $script = <<<HTML
<script>
new Vue({
    el: "{$this->el}",
    data: {$data},
    methods: {
        $ms
    }
});
</script>
HTML;
        return $script;
    }
}


