<?php
class graphql_index extends R\Page
{
    public function get()
    {
        $this->write("graphql");
    }

    public function post($system)
    {
        $server = new App\GQL\Server($this->app, $system);

        $input = (string) $this->request->getBody();
        $input = json_decode($input, true);

        $query = $input['query'];


        return $server->executeQuery($query);
    }
}
