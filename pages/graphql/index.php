<?php

use R\GraphQL\Schema;
use GraphQL\Error\Error;
use GraphQL\Language\Parser;
use GraphQL\Utils\BuildSchema;

class graphql_index extends R\Page
{
    public function get()
    {
        $this->write("api");
    }

    public function post()
    {
        $directiveResolvers = [
            "hasRole" => function ($next, $source, $args, $app) {
                if (!$args["role"]) {
                    return $next();
                }

                $allow = false;
                foreach ($args["role"] as $role) {
                    if (in_array($role, $app->role)) {
                        $allow = true;
                        break;
                    }
                }

                if ($allow) {
                    return $next();
                }

                throw new Error("access deny");
            }
        ];

        $this->app->role = [];
        if ($this->app->user) {
            foreach ($this->app->user->UserGroup() as $ug) {
                $this->app->role[] = (string) $ug;
            }
        }

        $loader = $this->app->loader;

        $loader->addPsr4("Type\\", __DIR__);
        $loader->register(true);

        $gql=file_get_contents($this->app->file("schema.gql"));
        try {
            $schema = Schema::Build($gql, $this->app);
    
        } catch (Exception $e) {
            return ["error" => [
                "message" => $e->getMessage()
            ]];
        }

        attachDirectiveResolvers($schema->schema, $directiveResolvers);


        $input = (string) $this->request->getBody();
        $input = json_decode($input, true);

        $query = $input['query'];
        $variableValues = $input['variables'];
        $schema->debug = true;


        return $schema->executeQuery($query, $variableValues);
    }
}
