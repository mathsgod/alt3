<?php

namespace Twig\Dynamic\ArrayList;


class Node extends \Twig\Node\Node
{

    public static $env;

    public function __construct(\Twig\Node\Expression\AbstractExpression $value,  $line, $tag = null)
    {
        parent::__construct(['type' => $value], [], $line, $tag);
    }

    public function compile(\Twig\Compiler $compiler)
    {
        self::$env = $compiler->getEnvironment();

        $type = $this->getNode("type");
        $name = $type->getAttribute("value");
        $compiler
            ->addDebugInfo($this)
            //->raw("echo strtoupper('$name')")
            ->raw("echo \Twig\Dynamic\Text\Node::Render('$name')")
            //->subcompile($this->getNode("type"))
            ->raw(";\n");
    }

    public static function Render($name)
    {
        $data = \Twig\Dynamic\Extension::$Data;

        return $data[$name];
    }
}
