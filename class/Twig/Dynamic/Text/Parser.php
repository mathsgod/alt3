<?php

namespace Twig\Dynamic\Text;

class Parser extends \Twig\TokenParser\AbstractTokenParser
{
    public function parse(\Twig\Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $value = $parser->getExpressionParser()->parseExpression();
        $stream->expect(\Twig\Token::BLOCK_END_TYPE);
        return new Node($value, $token->getLine(), $this->getTag());
    }

    public function getTag()
    {
        return "text";
    }
}
