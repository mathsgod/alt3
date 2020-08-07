<?php

namespace ALT\Element;

use Vue\Script;
use Vue\Scriptable;

class Card extends \Element\Card implements Scriptable
{
    protected static $NUM = 0;

    public function __construct()
    {
        parent::__construct();
        $this->setAttribute("id", "_element_card_" . self::$NUM);
        self::$NUM++;
    }

    public function script()
    {
        $script = new Script();
        $script->el = "#" . $this->getAttribute("id");

        foreach ($this->childNodes as $child) {
            if ($child instanceof Scriptable) {
                $script = $script->merge($child->script());
            }
        }
        return $script;
    }

    public function form($data = null)
    {
        $form = new Form();
        if ($data) {
            $form->setData($data);
        }
        $this->append($form);
        return $form;
    }
}
