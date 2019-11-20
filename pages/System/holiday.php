<?php

class System_holiday extends App\Page
{
    public function get($language = "zh-hk")
    {
        $holiday = new HL\Holiday($language);
        return $holiday->getHoliday("2000-01-01", date("Y-m-d"));
    }
}
