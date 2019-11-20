<?

class UserLog_truncate extends App\Page
{
    public function get()
    {
        App\UserLog::Query()->truncate();
        $this->alert->info("UserLog truncated");
        $this->redirect();
    }
}
