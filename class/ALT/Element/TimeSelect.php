<?php

namespace ALT\Element;

use Element\TimeSelect as ElementTimeSelect;

class TimeSelect extends ElementTimeSelect
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute(":picker-options", json_encode([
            "start" => "00:00",
            "end" => "23:30"
        ]));
    }

    public function start(string $start)
    {
        $option = json_decode($this->getAttribute(":picker-options"));
        $option["start"] = $start;
        $this->setAttribute(":picker-options", json_encode($option));
        return $this;
    }

    public function step(string $step)
    {
        $option = json_decode($this->getAttribute(":picker-options"));
        $option["step"] = $step;
        $this->setAttribute(":picker-options", json_encode($option));
        return $this;
    }

    public function end(string $end)
    {
        $option = json_decode($this->getAttribute(":picker-options"));
        $option["end"] = $end;
        $this->setAttribute(":picker-options", json_encode($option));
        return $this;
    }
}
