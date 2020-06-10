<?php
class EventLog_restore_object extends App\Page
{
    public function get()
    {
        $obj = $this->object();
        if ($obj->restoreObject()) {
            $this->alert->success("Restore successed");
        } else {
            $this->alert->danger("Restore failed");;
        }

        $this->redirect();
    }
}
