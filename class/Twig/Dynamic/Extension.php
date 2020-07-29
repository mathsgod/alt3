<?php

namespace Twig\Dynamic;

use Doctrine\Instantiator\Instantiator;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\FilterExpression;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\NameExpression;
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
                $arguments_node = iterator_to_array($expr->getNode("node")->getNode("arguments"));
                $args = $arguments_node[0] ? $this->getHashArugments($arguments_node[0]) : [];
                return [
                    "type" => "image",
                    "name" => $expr->getNode("node")->getNode("node")->getAttribute("name"),
                    "attributes" => $args
                ];
                break;
        }
    }



    public function getFilterExpression(FilterExpression $node, string $value_target)
    {

        $value = $node->getNode("filter")->getAttribute("value");

        if ($value == "text" || $value == "image") {

            if ($node->getNode("node") instanceof GetAttrExpression) {
                $get_attr_expression = $node->getNode("node");

                if ($get_attr_expression->getNode("node") instanceof NameExpression) {
                    $name_expression = $get_attr_expression->getNode("node");

                    if ($name_expression->getAttribute("name") == $value_target) {
                        return $node;
                    }
                }
            }
        } elseif ($value == "list") {
            if ($node->getNode("node") instanceof GetAttrExpression) {
                $get_attr_expression = $node->getNode("node");
                if ($get_attr_expression->getNode("node") instanceof NameExpression) {
                    $name_expression = $get_attr_expression->getNode("node");

                    if ($name_expression->getAttribute("name") == $value_target) {
                        return $node;
                    }
                }
            }
        }

        if ($node->getNode("node") instanceof FilterExpression) {
            return $this->getFilterExpression($node->getNode("node"), $value_target);
        }
    }

    public function filterTargetNode(Node $node, $value_target)
    {
        $rets = [];

        foreach ($node as $n) {
            if ($n instanceof PrintNode) {
                $expr = $n->getNode("expr");

                if ($this->getFilterExpression($expr, $value_target)) {
                    $rets[] = $n;
                }
            }

            if ($n instanceof ForNode) {
                $seq = $n->getNode("seq");
                if ($this->getFilterExpression($seq, $value_target)) {
                    $rets[] = $n;
                }
            }

            foreach ($this->filterTargetNode($n, $value_target) as $n) {
                $rets[] = $n;
            }
        }
        return $rets;
    }

    public function parseForNodeBody(ForNode $node): array
    {
        $value_target = $node->getNode("value_target")->getAttribute("name");
        $rets = [];
        foreach ($this->filterTargetNode($node, $value_target) as $n) {

            if ($n instanceof PrintNode) {

                $expr = $n->getNode("expr");
                $filter_expression = $this->getFilterExpression($expr, $value_target);
                $get_attr_expression = $filter_expression->getNode("node");

                $arguments_node = iterator_to_array($expr->getNode("node")->getNode("arguments"));
                $args = $arguments_node[0] ? $this->getHashArugments($arguments_node[0]) : [];

                $rets[] = [
                    "type" => "text",
                    "name" => $get_attr_expression->getNode("attribute")->getAttribute("value"),
                    "attributes" => $args

                ];
            }

            if ($n instanceof ForNode) {
                $rets[] = [
                    "type" => "list",
                    "name" => $n->getNode("value_target")->getAttribute("name"),
                    "body" => $this->parseForNodeBody($n)
                ];
            }
        }
        return $rets;
    }

    public function isValidedPrintNode(Node $node): bool
    {
        $value = $node->getNode("filter")->getAttribute("value");

        if ($value == "text" || $value == "image") {
            return true;
        }

        if ($node->getNode("node") instanceof FilterExpression) {
            return $this->isValidedPrintNode($node->getNode("node"));
        }
    }

    public function findAllNonForPrintNode(Node $node): array
    {
        $rets = [];
        foreach ($node as $n) {
            if ($n instanceof PrintNode) {
                if ($this->isValidedPrintNode($n->getNode("expr"))) {
                    $rets[] = $n;
                }
            }

            foreach ($this->findAllNonForPrintNode($n) as $nn) {
                $rets[] = $nn;
            }
        }
        return $rets;
    }

    public function getNodeType(Node $node)
    {
        $value = $node->getNode("filter")->getAttribute("value");

        if ($value == "text" || $value == "image") {
            return $value;
        }
        if ($node->getNode("node") instanceof FilterExpression) {
            return $this->getNodeType($node->getNode("node"));
        }
    }


    public function filterNameExpression(Node $node)
    {
        foreach ($node as $n) {
            if ($n instanceof NameExpression) {
                return $n;
            }
            return $this->filterNameExpression($n);
        }
    }

    public function getFilter(Node $node)
    {
        foreach ($node as $n) {
            if ($n instanceof FilterExpression) {
                $filter = $n->getNode("filter");
                $value = $filter->getAttribute("value");
                if ($value == "text" || $value == "image") {
                    return $n;
                }
            }
            return $this->getFilter($node);
        }
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
            foreach ($this->parseNode($node) as $n) {
                $rets[] = $n;
            }
        } else {

            foreach ($this->findAllNonForPrintNode($node) as $n) {
                outP((string)$n);
                die();
                $expr = $n->getNode("expr");
                $name_expression = $this->filterNameExpression($expr);
                $rets[] = [
                    "type" => $this->getNodeType($expr),
                    "name" => $name_expression->getAttribute("name")
                ];
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
