<?php

namespace Twig\Dynamic;

use Twig\Node\BodyNode;
use Twig\Node\ForNode;
use Twig\Node\Node;
use Twig\Node\PrintNode;

class Extension extends \Twig\Extension\AbstractExtension
{
    public static $Data = [];

    public function getTokenParsers()
    {
        return [new Text\Parser(), new Image\Parser(), new ArrayList\Parser()];
    }

    public function getFilters()
    {
        return [new Text\Filter(), new Image\Filter(), new ArrayList\Filter()];
    }

    private function findResult(Node $node)
    {
        $nodes = $this->filterNodes($node);
        $rets = [];

        foreach ($nodes as $n) {
            if ($n instanceof PrintNode) {
                $expr = $n->getNode("expr");
                $filter = $expr->getNode("node")->getNode("filter");;

                if ($filter->getAttribute("value") == "text") {
                    $rets[] = [
                        "type" => "text",
                        "name" => $expr->getNode("node")->getNode("node")->getNode("attribute")->getAttribute("value")
                    ];
                }

                if ($filter->getAttribute("value") == "image") {
                    $rets[] = [
                        "type" => "image",
                        "name" => $expr->getNode("node")->getNode("node")->getNode("attribute")->getAttribute("value")
                    ];
                }
            }
        }
        return $rets;
    }

    public function parse(string $code)
    {
        $loader = new \Twig\Loader\FilesystemLoader();
        $env = new \Twig\Environment($loader, ["debug" => true]);
        $env->addExtension($this);

        $stream = $env->tokenize(new \Twig\Source($code, "code"));
        $node = $env->parse($stream);

        $nodes = $this->filterNodes($node);


        foreach ($nodes as $n) {


            if ($n instanceof ForNode) {
                if ($n->getNode("seq")->getNode("filter")->getAttribute("value") == "list") {

                    $r = $this->findResult($n->getNode("body"));
                    $rets[] = [
                        "type" => "list",
                        "name" => $n->getNode("seq")->getNode("node")->getAttribute("name"),
                        "body" => $r
                    ];
                }
            }

            if ($n instanceof PrintNode) {
                $expr = $n->getNode("expr");
                $n = $expr->getNode("node")->getNode("filter");

                if ($n->getAttribute("value") == "text") {

                    $arguments_node = iterator_to_array($expr->getNode("node")->getNode("arguments"));

                    $arg_name = null;
                    $args = [];
                    foreach ($arguments_node[0] as $arg) {

                        if (is_null($arg_name)) {
                            $arg_name = $arg->getAttribute("value");
                            continue;
                        }

                        $args[$arg_name] = $arg->getAttribute("value");
                        $arg_name = null;
                    }


                    $rets[] = [
                        "type" => "text",
                        "name" => $expr->getNode("node")->getNode("node")->getAttribute("name"),
                        "attributes" => $args
                    ];
                }

                if ($n->getAttribute("value") == "image") {
                    $rets[] = [
                        "type" => "image",
                        "name" => $expr->getNode("node")->getNode("node")->getAttribute("name")
                    ];
                }
            }


            if ($n instanceof ArrayList\Node) {
                $r = [
                    "type" => "list",
                    "name" => $n->getNode("type")->getAttribute(("value")),
                    "body" => []
                ];

                $body = $n->getNode("body");
                foreach ($body as $child) {
                    if ($child instanceof Image\Node) {
                        $r["body"][] = [
                            "type" => "image",
                            "name" => $child->getNode("type")->getAttribute(("value"))
                        ];
                    }
                    if ($child instanceof Text\Node) {
                        $r["body"][] = [
                            "type" => "text",
                            "name" => $child->getNode("type")->getAttribute(("value"))
                        ];
                    }
                }

                $rets[] = $r;
                continue;
            }

            if ($n instanceof Image\Node) {

                $rets[] = [
                    "type" => "image",
                    "name" => $n->getNode("type")->getAttribute(("value"))
                ];
            }

            if ($n instanceof Text\Node) {

                $rets[] = [
                    "type" => "text",
                    "name" => $n->getNode("type")->getAttribute(("value"))
                ];
            }
        }

        //outP($rets);
        //die();

        return $rets;
    }

    private function filterNodes($node)
    {
        $ret = [];

        foreach ($node as $n) {
            if ($n instanceof ForNode) {
                $ret[] = $n;
                continue;
            }

            if ($n instanceof PrintNode) {
                $ret[] = $n;
                continue;
            }

            if ($n instanceof Image\Node) {
                $ret[] = $n;
                continue;
            }
            if ($n instanceof Text\Node) {
                $ret[] = $n;
                continue;
            }
            if ($n instanceof ArrayList\Node) {
                $ret[] = $n;
                continue;
            }

            foreach ($this->filterNodes($n) as $child) {
                $ret[] = $child;
            }
        }
        return $ret;
    }

    private function getAllTwigChildNode($node)
    {
        $nodes = $node->getIterator();
        $ret = [];
        foreach ($nodes as $node) {
            $ret[] = $node;

            foreach ($this->getAllTwigChildNode($node) as $n) {
                $ret[] = $n;
            }
        }
        return $ret;
    }

    public static function SetData(array $data)
    {
        self::$Data = $data;
    }
}
