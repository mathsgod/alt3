<?php

namespace Twig\Dynamic;

use App\Translate;
use ArrayObject;
use Doctrine\Instantiator\Instantiator;
use Traversable;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\FilterExpression;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\ForNode;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Node\TextNode;

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

    /**
     * parse normal print node, if this print node is not a seq of for, ignore it
     */
    private function parsePrintNode(PrintNode $node)
    {
        $filter = $this->getFilter($node);
        return  [
            "type" => $filter->getNode("filter")->getAttribute("value"),
            "name" => $this->getNodeName($node),
            "attributes" => $this->getArgument($filter)
        ];
    }


    public function processForPrintNode(Node $node, string $value_target): array
    {
        $rets = [];
        foreach ($node as $name => $n) {
            if ($n instanceof PrintNode) {
                if ($this->getContextName($n) == $value_target) {

                    $filter_expression = $this->getFilter($n);
                    $rets[] = [
                        "type" => $filter_expression->getNode("filter")->getAttribute("value"),
                        "name" => $filter_expression->getNode("node")->getNode("attribute")->getAttribute("value"),
                        "attributes" => $this->getArgument($filter_expression)
                    ];

                    $node->removeNode($name);
                }
            }
            foreach ($this->processForPrintNode($n, $value_target) as $c) {
                $rets[] = $c;
            }
        }

        return $rets;
    }

    public function processForForNode(Node $node, string $value_target): array
    {
        $rets = [];
        foreach ($node as $name => $n) {

            if ($n instanceof ForNode) {

                if ($this->getContextName($n) == $value_target) {
                    $rets[] = [
                        "type" => "list",
                        "name" => $this->getNodeName($n),
                        "body" => $this->parseForNodeBody($n)
                    ];

                    $node->setNode($name, $n->getNode("body"));
                }
            }
            foreach ($this->processForForNode($n, $value_target) as $c) {
                $rets[] = $c;
            }
        }
        return $rets;
    }

    public function parseForNodeBody(ForNode $node): array
    {
        $value_target = $node->getNode("value_target")->getAttribute("name");
        $body = $node->getNode("body");

        $rets = [];
        foreach ($this->processForPrintNode($body, $value_target) as $r) {
            $rets[] = $r;
        }

        foreach ($this->processForForNode($body, $value_target) as $r) {
            $rets[] = $r;
        }

        return $rets;
    }


    public function getNameExpression(Node $node)
    {
        foreach ($node as $n) {
            if ($n instanceof NameExpression) {
                return $n;
            }
            if ($r = $this->getNameExpression($n)) {
                return $r;
            }
        }
    }

    public function getFilter(Node $node)
    {
        if ($node instanceof FilterExpression) {
            $filter = $node->getNode("filter");
            $value = $filter->getAttribute("value");
            if ($value == "text" || $value == "image" || $value == "list") {
                return $node;
            }
        }

        foreach ($node as $n) {
            if ($r = $this->getFilter($n)) {
                return $r;
            }
        }
    }

    public function getArgument(FilterExpression $node): array
    {
        if (!$node->hasNode("arguments")) return [];
        $rets = [];

        foreach ($node->getNode("arguments") as $args) {
            if ($args instanceof ArrayExpression) {

                $arg_name = null;
                $r = [];
                foreach ($args as $n) {

                    if (is_null($arg_name)) {
                        $arg_name = $n->getAttribute("value");
                        continue;
                    }

                    $r[$arg_name] = $n->getAttribute("value");
                    $arg_name = null;
                }

                $rets[] = $r;
            }
        }

        return $rets;
    }

    public function getContextName(Node $node):string
    {
        $filter = $this->getFilter($node);
        if ($filter) {
            $name_expression = $this->getNameExpression($filter);
            return $name_expression->getAttribute("name");
        }
        return "";
    }


    public function getNodeName(Node $node): string
    {
        $filter = $this->getFilter($node);
        $name_expression = $this->getNameExpression($filter);
        return $name_expression->getAttribute("name");
    }

    /**
     * parse ForNode and return array of result
     */
    public function parseForNode(ForNode $node): array
    {
        return [
            "type" => "list",
            "name" => $this->getNodeName($node),
            "body" =>  $this->parseForNodeBody($node)
        ];
    }

    public function removeNode(Node $node)
    {
        foreach ($node as $name => $n) {
            if ($n instanceof TextNode) {
                $node->removeNode($name);
                continue;
            }


            if ($n instanceof PrintNode) {
                //remove non valid filter
                if (!$this->getFilter($n)) {
                    $node->removeNode($name);
                }
            }

            $this->removeNode($n);
        }


        foreach ($node as $name => $n) {
            if ($n instanceof ForNode) {
                $filter = $this->getFilter($n->getNode("seq"));

                if (!$filter) { //normal for loop
                    $node->setNode($name,  $n->getNode("body"));
                }
            }

            $this->removeNode($n);
        }
    }

    public function parseNode(Node $node): array
    {

        $rets = [];
        foreach ($node as $name => $n) {
            if ($n instanceof ForNode) {
                $rets[] = $this->parseForNode($n);
            }

            if ($n instanceof PrintNode) {
                $rets[] = $this->parsePrintNode($n);
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
        $node = $node->getNode("body");

        $this->removeNode($node);
        return $this->parseNode($node);
    }
}
