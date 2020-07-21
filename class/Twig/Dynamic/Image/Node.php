<?php

namespace Twig\Dynamic\Image;

class Node extends \Twig\Node\Node
{
    public function __construct(\Twig\Node\Expression\AbstractExpression $value, array $attributes = [], $line, $tag = null)
    {
        parent::__construct(['type' => $value], $attributes, $line, $tag);
    }

    public function compile(\Twig\Compiler $compiler)
    {
        $type = $this->getNode("type");
        $name = $type->getAttribute("value");

        $attr = [];
        if ($this->hasAttribute("width")) {
            $attr["width"] = $this->getAttribute("width");
        }
        if ($this->hasAttribute("height")) {
            $attr["height"] = $this->getAttribute("height");
        }


        $compiler
            ->addDebugInfo($this)
            ->raw("echo \Twig\Dynamic\Image\Node::Render('$name','" . json_encode($attr) . "')")
            //->subcompile($this->getNode("type"))
            ->raw(";\n");
    }

    public static function Render($name, $attr)
    {
        $data = \Twig\Dynamic\Extension::$Data;
        $attr = json_decode($attr,true);


        $img = html("img");
        $img->src("/client_uploads/" . $data[$name]);

        $img->style($attr);

        return (string)$img;
    }
}
