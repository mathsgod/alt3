<?php

namespace App;

class Mail extends Core\Mail
{
    public function send()
    {
        foreach ($this->to as $to) {
            $l = new MailLog();
            $l->subject = $this->Subject;

            $l->from = $this->From;
            $l->from_name = $this->FromName;
            $l->to = $to[0];
            $l->to_name = $to[1];
            $l->body = $this->Body;
            $l->altbody = $this->AltBody;

            $l->host = $this->Host;

            $l->save();
        }

        return parent::send();
    }
}
