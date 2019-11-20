<?php
// Created By: Raymond Chong
// Created Date: 19/2/2010
// Last Updated:
class SystemBackup_do_run extends App\Page
{
    public function get()
    {
        $config = $this->app->config["database"];
        $dbname = $config["username"];
        $dbuser = $config["database"];
        $dbpassword = $config["password"];

        system("mysqldump --user {$dbuser} --password={$dbpassword} {$dbname} > " . getcwd() . "/backup/" . date("YmdHis") . ".sql");
        $this->alert->info("Backup completed");
        $this->redirect("SystemBackup");
    }
}
