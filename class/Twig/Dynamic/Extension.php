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

    public function findAllPrintNode(Node $node, string $value_target): array
    {
        $rets = [];

        foreach ($node as $n) {
            if ($n instanceof PrintNode) {
                $node = $n->getNode("expr")->getNode("node");
                if (!$node->hasNode("filter")) continue; //normal print node
                $name_expression = $node->getNode("node");
                if ($name_expression->hasNode("node")) {
                    if ($name_expression->getNode("node")->getAttribute("name") == $value_target) {
                        $rets[] = $n;

                        continue;
                    }
                }
            }

            foreach ($this->findAllPrintNode($n, $value_target) as $n) {
                $rets[] = $n;
            }
        }
        return $rets;
    }

    public function parseForNodeBody(ForNode $node): array
    {
        $value_target = $node->getNode("value_target")->getAttribute("name");

        $body = iterator_to_array($node->getNode("body"))[0];
        $rets = [];

        foreach ($this->findAllPrintNode($body, $value_target) as $n) {
            $node = $n->getNode("expr")->getNode("node");


            if (!$node->hasNode("filter")) continue; //normal print node

            $name_expression = $node->getNode("node");
            if ($name_expression->hasNode("node")) {
                if ($name_expression->getNode("node")->getAttribute("name") == $value_target) {
                    $rets[] = [
                        "type" => "text",
                        "name" => $name_expression->getNode("attribute")->getAttribute("value")
                    ];
                }
            }
        }

        foreach ($body as $n) {

            if ($n instanceof ForNode) {
                $seq = $n->getNode("seq");
                $name_expression = $seq->getNode("node")->getNode("node");
                if ($name_expression->getAttribute("name") == $value_target) {
                    $rets[] = [
                        "type" => "list",
                        "name" => $seq->getNode("node")->getNode("attribute")->getAttribute("value"),
                        "body" => $this->parseForNodeBody($n)
                    ];
                }
            }
        }

        return $rets;
    }

    /**
     * parse ForNode and return array of result
     */
    public function parseForNode(ForNode $node): array
    {
        $rets = [];

        $seq = $node->getNode("seq");
        if ($seq->hasNode("filter") && $seq->getNode("filter")->getAttribute("value") == "list") {

            $rets[] = [
                "type" => "list",
                "name" => $seq->getNode("node")->getAttribute("name"),
                "body" =>  $this->parseForNodeBody($node)
            ];
        } else {
            foreach ($this->parseNode($node) as $r) {
                $rets[] = $r;
            }
        }
        return $rets;
    }

    public function parseNode(Node $node): array
    {

        $rets = [];
        foreach ($node as $n) {

            if ($n instanceof ForNode) {
                foreach ($this->parseForNode($n) as $r) {
                    $rets[] = $r;
                }
                continue;
            }

            if ($n instanceof PrintNode) {
                $rets[] = $this->parsePrintNode($n);
                continue;
            }

            foreach ($this->parseNode($n) as $r) {
                $rets[] = $r;
                continue;
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

        return $this->parseNode($node);
    }
}
