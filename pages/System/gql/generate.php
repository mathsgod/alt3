<?


class System_gql_generate extends ALT\Page
{
    public function get()
    {
        $gen = new GQL\Generator($this->app->db);


        $card = $this->createCard();
        p($card->body)->html("<pre>" . $gen->output() . "</pre>");
        $this->write($card);
    }
}
