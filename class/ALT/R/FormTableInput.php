<?php

namespace ALT\R;

use Element\Input;

class FormTableInput extends Input
{

    public function required()
    {
        $this->setAttribute("required", true);
        return;
    }
}
