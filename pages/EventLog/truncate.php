<?php
use App\EventLog;

class EventLog_truncate extends App\Page
{
    public function get()
    {
        EventLog::Query()->truncate();
        $this->alert->info("EventLog tables truncated");
        $this->redirect();
    }
}
