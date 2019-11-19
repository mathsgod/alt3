<?php
namespace My;
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer;
class Mail extends PHPMailer {
    public function __construct($exceptions = false) {
        $this->CharSet = "UTF-8";
        parent::__construct($exceptions);
    }
}