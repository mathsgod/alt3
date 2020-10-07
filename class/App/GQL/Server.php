<?php

namespace App\GQL;

use R\GraphQL\Schema;
use GraphQL\Error\Error;
use GraphQL\Language\Parser;
use GraphQL\Utils\BuildSchema;
use Exception;

class Server
{

    public $schema;

    public function __construct($app, $system = false)
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

        $app->role = [];
        if ($app->user) {
            foreach ($app->user->UserGroup() as $ug) {
                $app->role[] = (string) $ug;
            }
        }
        $app->me = $app->user;
        if ($system) {
            $gql = file_get_contents($app->system_root . "/schema.gql");
            Schema::$Namespace = "\\App\\GQL\\";
        } else {
            $gql = file_get_contents($app->file("schema.gql"));
        }

        try {
            $this->schema = Schema::Build($gql, $app);
        } catch (Exception $e) {
            return ["error" => [
                "message" => $e->getMessage()
            ]];
        }

        attachDirectiveResolvers($this->schema->schema, $directiveResolvers);

        $this->schema->debug = true;
    }

    public function executeQuery(string $query)
    {
        $r = $this->schema->executeQuery($query);
        return $r;
    }
}
