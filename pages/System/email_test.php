<?php
// Created By: Raymond Chong
// Created Date: 30/05/2012
// Last Updated:
class System_email_test extends ALT\Page
{
    public function post()
    {
        $mail = $this->createMail();
        $mail->isSMTP();
        $mail->Subject = $_POST["subject"];
        $mail->setFrom($_POST["sender"]);
        $mail->addAddress($_POST["receiver"]);

        if ($_POST["cc"]) $mail->addCC($_POST["cc"]);
        if ($_POST["bcc"]) $mail->addBCC($_POST["bcc"]);
        $mail->msgHTML($_POST["content"]);

        if ($_POST["smtp"]) {
            $mail->Host = $_POST["smtp"];
            $mail->Port = 25;
            $mail->SMTPAuth = true;
            $mail->Username = $_POST["smtp-username"];
            $mail->Password = $_POST["smtp-password"];
        }

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
        $mv->add("smtp")->input("smtp");
        $mv->add("smtp-username")->input("smtp-username");
        $mv->add("smtp-password")->input("smtp-password");
        $mv->add("return-path")->input("return-path");

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