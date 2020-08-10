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
        $encoded_str = js_object_encode($this);
        return <<<HTML
<script>
new Vue($encoded_str);
</script>
HTML;
    }
}
