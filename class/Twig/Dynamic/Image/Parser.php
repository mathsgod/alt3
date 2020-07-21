<?php

namespace Twig\Dynamic\Image;

class Parser extends \Twig\TokenParser\AbstractTokenParser
{
    public function parse(\Twig\Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $name = $parser->getExpressionParser()->parseExpression();
        $attr = [];

        if (!$stream->test(\Twig\Token::BLOCK_END_TYPE)) {
            while ($token = $stream->next()) {
                //            $name = $stream->expect(\Twig\Token::STRING_TYPE)->getValue();
                $k = $token->getValue();
                $stream->expect(\Twig\Token::OPERATOR_TYPE, '=');

                $v = $stream->expect(\Twig\Token::STRING_TYPE)->getValue();

                $attr[$k] = $v;
                if ($stream->test(\Twig\Token::BLOCK_END_TYPE)) {
                    break;
                }
            }
        }

        $stream->expect(\Twig\Token::BLOCK_END_TYPE);
        return new Node($name, $attr, $token->getLine(), $this->getTag());
    }

    public function getTag()
    {
        return "image";
    }
}
