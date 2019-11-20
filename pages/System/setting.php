<?php

class System_Setting extends ALT\Page {
    public function post() {
        App\Config::_("forget pwd email/subject", $_POST["forget_pwd_email/subject"]);
        App\Config::_("forget pwd email/content", $_POST["forget_pwd_email/content"]);
        // parent::post();
        App::Redirect("System/setting");
    }

    public function get() {

        $v = My::E(App::Config("user"));
        $v->add("Forget password email subject")->input("forget pwd email/subject");
        $v->add("Forget password email template")->textarea("forget pwd email/content")->rows(10);

        $this->write($this->createForm($v));
    }
}

?>