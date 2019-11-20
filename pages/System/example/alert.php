<?

class System_example_alert extends ALT\Page
{
    public function get()
    {
        $this->alert->danger("danger", "danger description");
        $this->alert->info("info", "info description");
        $this->alert->warning("warning", "warning description");
        $this->alert->success("success", "success description");

        $this->callout->danger("danger", "danger description");
        $this->callout->info("info", "info description");
        $this->callout->warning("warning", "warning description");
        $this->callout->success("success", "success description");
    }
}
