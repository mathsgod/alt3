<?



class Dashboard_test extends ALT\Page
{
    public function get()
    {

        return;
        $card = $this->createCard();
        $card->setAttribute("primary", true);
        $card->setAttribute("outline", true);

        $card->body()->append("body");

        $this->write($card);

        return;
        outp($this->app->modules());
        outp($this->app->user);

        $this->write("tst");
    }
}
