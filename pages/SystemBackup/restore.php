<?php
// Created By: Raymond Chong
// Created Date: 20/8/2010
// Last Updated:
class SystemBackup_restore extends Page
{
    public function get()
    {
        $this->object()->Restore();
        $this->alert->info("Database restored");
        $this->redirect("SystemBackup");
    }
}