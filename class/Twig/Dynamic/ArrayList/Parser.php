<?php

namespace Twig\Dynamic\ArrayList;

use Twig\Node\BodyNode;

class Parser extends \Twig\TokenParser\AbstractTokenParser
{
    public function parse(\Twig\Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $value = $parser->getExpressionParser()->parseExpression();
        $stream->expect(\Twig\Token::BLOCK_END_TYPE);

        $continue = true;
        $node = new Node($value, $token->getLine(), $this->getTag());

        $body_node = [];

        while ($continue) {
            $body = $this->parser->subparse([$this, "decideEnd"]);

            $token = $stream->next();
            $tag = $token->getValue();
            switch ($tag) {
                case "endlist":
                    $stream->expect(\Twig\Token::BLOCK_END_TYPE);
                    $continue = false;
                    break;
                case "text":
                    $text = $parser->getExpressionParser()->parseExpression();
                    $text_node = new \Twig\Dynamic\Text\Node($text, $token->getLine(), "text");
                    $body_node[] = $text_node;
                    $stream->expect(\Twig\Token::BLOCK_END_TYPE);
                    break;
                    
            }
        }

        $body = new BodyNode($body_node);
        $node->setNode("body", $body);


        return $node;
    }

    public function decideEnd(\Twig\Token $token)
    {
        return $token->test(["endlist", "text", "image"]);
    }

    public function getTag()
    {
        return "list";
    }
}
