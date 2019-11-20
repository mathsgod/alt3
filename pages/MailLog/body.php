<?php

class MailLog_body extends App\Page {
    public function get() {


        $this->write($this->object()->body);
    }
}

?>