<?php

namespace JS;

class ObjectClass
{

    public function __toString()
    {
        return js_object_encode($this);
    }
}
