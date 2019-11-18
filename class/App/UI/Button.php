<?php

namespace App\UI;

use App\Page;

class Button extends \P\HTMLButtonElement
{
    private $page;
    public function __construct(Page $page, $href = null)
    {
        parent::__construct();
        $this->page = $page;
        $this->classList->add("btn");
    }

    public function text($text)
    {
        return parent::text($this->page->translate($text));
    }

    public function __toString()
    {
        if ($href = $this->attr("href")) {
            if (!\App\ACL::Allow($href)) {
                return "";
            }
        }

        return parent::__toString();
    }

    public function icon($icon): Button
    {
        $this->append(p("<i class='$icon'></i>"));
        return $this;
    }


    public function label($label)
    {
        $text_node = [];
        foreach ($this->childNodes as $node) {
            if ($node instanceof \P\Text) {
                $text_node[] = $node;
            }
        }

        foreach ($text_node as $n) {
            $this->removeChild($n);
        }

        $this->append(new \P\Text(" " . $this->page->translate($label)));

        return $this;
    }

    public function fancybox()
    {
        $this->attr("data-type", "ajax");
        $this->attr("data-fancybox", true);
        return $this;
    }
}
