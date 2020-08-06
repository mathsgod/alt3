<?php

class VueScript
{

    public $data = [];

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
