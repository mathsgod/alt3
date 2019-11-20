<?php

use HL\Holiday;

class Dashboard_holiday extends App\Page
{
    public function get($start, $end)
    {
        $h = new Holiday($this->app->user->language);
        $holiday = [];
        foreach ($h->getHoliday($start, $end) as $d) {
            $c = [];
            $c["title"] = $d["name"];
            $c["start"] = $d["date"];
            $c["color"] = "red";
            $c["textColor"] = "white";
            $holiday[] = $c;
        }
        return $holiday;
    }
}
