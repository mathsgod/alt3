<?php

use App\Config;

class Config_list extends App\Page
{
    public function get()
    {
        App\UI\Card::$NUM = 1000;
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
                    if (is_array($o[1])) {
                        return json_encode($o[1], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }
                    return $o[1];
                });
            }
            $t->header($category);
            $this->write($t);
            $this->write($t->script());
        }
    }
}
