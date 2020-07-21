<?php

namespace App\UI;

use Psr\Http\Message\RequestInterface;

class RTRequest
{
    private $request;
    private $query = [];

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $uri = $request->getUri();
        parse_str($uri->getQuery(), $this->query);
    }

    //Queryable
    public function setDataSource($source)
    {
        $this->source = $source;
    }

    public function filteredSource()
    {
        $source = clone $this->source;

        foreach ($this->query["filter"] as $filter) {
            switch ($filter["method"]) {
                case "like":
                    $name = $filter["name"];
                    $source->where("$name like :$name", [$name => "%".$filter["value"]."%"]);
            }
        }
        return $source;
    }

    public function data()
    {

        return $this->filteredSource();
    }
}
