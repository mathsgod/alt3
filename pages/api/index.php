<?php

use R\GraphQL\Schema;

class api_index extends R\Page
{
    public function post()
    {
        $loader = $this->app->loader;
        $loader->addPsr4("Type\\", __DIR__ . "/Type");
        $loader->register();
        $schema = Schema::Build(file_get_contents(__DIR__ . "/schema.gql"), $this->app);

        $input = (string) $this->request;
        $input = json_decode($input, true);

        $query = $input['query'];
        $variableValues = $input['variables'];

        return $schema->executeQuery($query, $variableValues);
    }
}
