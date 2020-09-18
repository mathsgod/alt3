<?php

class System_code extends App\Page
{
    public function get($source)
    {
        $pi = $this->app->pathInfo();
        $this->data["system_base"] = $pi["system_base"];
    }
}
