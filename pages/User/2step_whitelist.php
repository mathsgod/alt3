<?php

class User_2step_whitelist extends ALT\Page
{
    public function post()
    {
        $u=App::User();
        $setting=$u->Setting();

        $list=explode("\n", $_POST["2-step_ip_white_list"]);
        array_walk($list, "trim");

        $setting["2-step_ip_white_list"]=$list;
        
        $u->setting=json_encode($setting, JSON_UNESCAPED_UNICODE);
        $u->save();
        $this->redirect();
    }

    public function get()
    {
        $this->callout->info("Your current ip address: ".$this->request->getServerParams()["REMOTE_ADDR"]);
        $o=$this->app->user;
        $setting=$o->setting();
        $d["2-step_ip_white_list"]=implode("\n",$setting["2-step_ip_white_list"]);
        $e=$this->createE($d);
        $e->add("2 step IP whilte list")->textarea('2-step_ip_white_list')->attr('placeholder', '一行一個')->attr("rows", 6);
        $this->write($this->createForm($e));
    }
}
