<?php
// Created By: Raymond Chong
// Created Date: 30/05/2012
// Last Updated:
class System_email_test extends ALT\Page
{
    public function post()
    {
        $mail = $this->app->createMail();
        $mail->Subject = $_POST["subject"];
        $mail->setFrom($_POST["sender"]);
        $mail->addAddress($_POST["receiver"]);
        if ($_POST["cc"]) $mail->addCC($_POST["cc"]);
        if ($_POST["bcc"]) $mail->addBCC($_POST["bcc"]);
        $mail->msgHTML($_POST["content"]);
        if ($mail->send()) {
            $this->app->alert->success("mail sent");
        } else {
            $this->app->alert->danger($mail->ErrorInfo);
        }
        $this->redirect();
    }

    public function get()
    {
        $config = $this->app->config["user"];

        $config["sender"] = "raymond@hostlink.com.hk";
        $config["receiver"] = "raymond@hostlink.com.hk";
        $config["cc"] = "";
        $config["bcc"] = "";
        $config["subject"] = "subject";
        $config["content"] = "This is a test mail";

        $mv = $this->createE($config);
        $mv->add("smtp", "smtp");
        $mv->add("smtp-username", "smtp-username");
        $mv->add("smtp-password", "smtp-password");
        $mv->add("smtp-port", "smtp-port");
        //$mv->add("return-path")->input("return-path");

        $mv->addHr();
        $mv->add("Sender")->input("sender");
        $mv->add("Receiver")->input("receiver");
        $mv->add("CC")->input("cc");
        $mv->add("BCC")->input("bcc");
        $mv->add("Subject")->input("subject");
        $mv->add("Content")->textarea("content");

        $this->write($this->createForm($mv));
    }
}
