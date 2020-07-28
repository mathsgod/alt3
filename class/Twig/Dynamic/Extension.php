<?php

namespace Twig\Dynamic;

use GraphQL\Utils\FindBreakingChanges;
use Twig\Node\BodyNode;
use Twig\Node\Expression\ArrayExpression;
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

    private function findNonChildInFor(Node $node)
    {
        $nodes = $this->filterNodes($node);
        $rets = [];
        foreach ($nodes as $n) {
            if ($n instanceof PrintNode) {
                $expr = $n->getNode("expr");
                if (!$expr->getNode("node")->hasNode("filter")) continue;
                $filter = $expr->getNode("node")->getNode("filter");
                if (!$expr->getNode("node")->getNode("node")->hasAttribute("name")) continue;

                $rets[] = $this->parsePrintNode($n);
            }
            if ($n instanceof ForNode) {

                foreach ($this->findForBody($n->getNode("body")) as $child) {
                    $rets[] = $child;
                }

                foreach ($this->findNonChildInFor($n->getNode("body")) as $child) {
                    $rets[] = $child;
                }
            }
        }
        return $rets;
    }

    private function getHashArugments(ArrayExpression $node): array
    {
        $arg_name = null;
        $args = [];
        foreach ($node as $arg) {

            if (is_null($arg_name)) {
                $arg_name = $arg->getAttribute("value");
                continue;
            }

            $args[$arg_name] = $arg->getAttribute("value");
            $arg_name = null;
        }
        return $args;
    }

    private function findForBody(Node $node): array
    {

        $nodes = iterator_to_array($node);

        //$nodes = $this->filterNodes($node);
        $rets = [
            "global" => [],
            "body" => []
        ];

        foreach ($nodes[0] as $n) {
            if ($n instanceof ForNode) {

                $rets[] = [
                    "type" => "list",
                    "name" => $n->getNode("seq")->getNode("node")->getNode("attribute")->getAttribute("value"),
                    "body" => $this->findForBody($n->getNode("body"))
                ];
            }

            if ($n instanceof PrintNode) {

                $expr = $n->getNode("expr");
                if (!$expr->getNode("node")->getNode("node")->hasNode("attribute")) {
                    $rets["global"][] = $this->parsePrintNode($n);
                    continue;
                }

                $filter = $expr->getNode("node")->getNode("filter");

                switch ($filter->getAttribute("value")) {
                    case "text":

                        $arguments_node = iterator_to_array($expr->getNode("node")->getNode("arguments"));
                        $args = $arguments_node[0] ? $this->getHashArugments($arguments_node[0]) : [];
                        $rets["body"][] = [
                            "type" => "text",
                            "name" => $expr->getNode("node")->getNode("node")->getNode("attribute")->getAttribute("value"),
                            "attributes" => $args
                        ];
                        break;
                    case "image":
                        $rets["body"][] = [
                            "type" => "image",
                            "name" => $expr->getNode("node")->getNode("node")->getNode("attribute")->getAttribute("value")
                        ];
                        break;
                }
            }
        }
        return $rets;
    }

    private function parsePrintNode(PrintNode $node): array
    {
        $expr = $node->getNode("expr");
        $filter = $expr->getNode("node")->getNode("filter");

        switch ($filter->getAttribute("value")) {
            case "text":
                $arguments_node = iterator_to_array($expr->getNode("node")->getNode("arguments"));
                $args = $arguments_node[0] ? $this->getHashArugments($arguments_node[0]) : [];
                return  [
                    "type" => "text",
                    "name" => $expr->getNode("node")->getNode("node")->getAttribute("name"),
                    "attributes" => $args
                ];
                break;
            case "image":
                return [
                    "type" => "image",
                    "name" => $expr->getNode("node")->getNode("node")->getAttribute("name")
                ];
                break;
        }
    }

    /**
     * parse ForNode and return array of result
     */
    public function parseForNode(ForNode $node): array
    {
        $rets = [];

        if ($node->getNode("seq")->hasNode("filter") && $node->getNode("seq")->getNode("filter")->getAttribute("value") == "list") {
            
            
            //filter all 
            outP($node);
            die();

            $r = $this->findForBody($node->getNode("body"));

            foreach ($r["global"] as $child) {
                $rets[] = $child;
            }

            $rets[] = [
                "type" => "list",
                "name" => $node->getNode("seq")->getNode("node")->getAttribute("name"),
                "body" => $r["body"]
            ];
        } else {
            foreach ($node->getNode("body") as $node) {
                foreach ($node as $n) {
                    if ($n instanceof ForNode) {
                        foreach ($this->parseForNode($n) as $child) {
                            $rets[] = $child;
                        }
                    }

                    if ($n instanceof PrintNode) {
                        $rets[] = $this->parsePrintNode($n);
                    }
                }
            }
        }
        return $rets;
    }

    /**
     * parse the twig structure
     */
    public function parse(string $code): array
    {
        $loader = new \Twig\Loader\FilesystemLoader();
        $env = new \Twig\Environment($loader, ["debug" => true]);
        $env->addExtension($this);

        $stream = $env->tokenize(new \Twig\Source($code, "code"));
        $node = $env->parse($stream);
        $nodes = $this->filterNodes($node);

        $rets = [];
        foreach ($nodes as $n) {
            if ($n instanceof ForNode) {
                foreach ($this->parseForNode($n) as $res) {
                    $rets[] = $res;
                }
            }

            if ($n instanceof PrintNode) {
                $rets[] = $this->parsePrintNode($n);
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
                $expr = $n->getNode("expr");
                if (!$expr->getNode("node")->hasNode("filter")) continue;
                $ret[] = $n;
                continue;
            }

            if ($n instanceof Image\Node) {
                $expr = $n->getNode("expr");
                if (!$expr->getNode("node")->hasNode("filter")) continue;
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
