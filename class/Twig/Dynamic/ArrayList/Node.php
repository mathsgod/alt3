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


        $compiler->addDebugInfo($this)

            ->write("\$context['_parent'] = \$context;\n")
            ->write("\$context['_seq'] = \$context['$name'];\n");

        $compiler
            ->write("foreach (\$context['_seq'] as \$context['_child']){")
            ->indent()

            ->subcompile($this->getNode('body'))
            ->outdent()
            ->write("}\n");


            /*

        $compiler->write("\$_parent = \$context['_parent'];\n");

        // remove some "private" loop variables (needed for nested loops)
        $compiler->write('unset($context[\'_seq\'], $context[\'_parent\'], $context[\'loop\']);' . "\n");

        // keep the values set in the inner context for variables defined in the outer context
        $compiler->write("\$context = array_intersect_key(\$context, \$_parent) + \$_parent;\n");
*/


        outp($compiler->getSource());
        die();
    }
}
