<?


class System_gql_generate extends ALT\Page
{
    public function get()
    {
        $gen = new GQL\Generator($this->app->db);

        $this->write("<pre>");
        $this->write($gen->output());
        $this->write("</pre>");
    }
}
