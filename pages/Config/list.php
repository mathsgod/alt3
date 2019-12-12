<?php

use App\Config;

class Config_list extends App\Page
{
    public function get()
    {

        foreach ($this->app->config as $category => $config) {
            $c = [];
            foreach ($config as $k => $v) {
                $c[] = [$k, $v];
            }

            $t = $this->createT($c);

            $t->add("Name", function ($o) {
                return $o[0];
            });

            if ($category == "user") {
                $t->add("Value", function ($o) {
                    $input = html("x-input");
                    $input->attr([
                        "data-url" => "Config/update",
                        "data-name" => $o[0],
                        "value" => $o[1]
                    ]);
                    return $input;
                });
            } else {
                $t->add("Value", function ($o) {
                    return $o[1];
                });
            }
            $t->header($category);
            $this->write($t);
        }
    }
}
