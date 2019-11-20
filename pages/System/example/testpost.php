<?

class System_example_testpost extends ALT\Page
{
    public function post()
    {
        outp("hello");
        $this->alert->info("hello");
        $this->redirect("Dashboard");
    }

    public function get()
    {
        $this->write($this->createForm("hello"));
    }
}
