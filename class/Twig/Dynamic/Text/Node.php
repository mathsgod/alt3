<?php

namespace Twig\Dynamic\Text;


class Node extends \Twig\Node\Node
{

    public static $env;

    public function __construct(\Twig\Node\Expression\AbstractExpression $value, $line, $tag = null)
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
            ->write('echo $context[\'' . $name . '\']')
            ->raw(";\n");
    }
}
