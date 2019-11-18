<?



class Dashboard_test extends ALT\Page
{
    public function get()
    {

        outp($this->app->modules());
        outp($this->app->user);

        $this->write("tst");
    }
}
