<?php

namespace Twig\Dynamic;

use Twig\Node\BodyNode;
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
        return [new Text\Filter()];
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

            if ($n instanceof PrintNode) {
                print_r($n);
                die();
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
            if($n instanceof PrintNode){
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
