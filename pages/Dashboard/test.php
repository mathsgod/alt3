<?



class Dashboard_test extends ALT\Page
{
    public function get()
    {


        return;
        outp($this->app->modules());
        outp($this->app->user);

        $this->write("tst");
    }
}
