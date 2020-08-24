<?php

namespace ALT;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use PHP\Psr7\ObjectStream;

class GridPage extends Page
{
    protected $_grid;

    public function write($element)
    {
        $this->_grid->add($element, [0, 0]);
    }

    public function __invoke(RequestInterface  $request, ResponseInterface $response): ResponseInterface
    {
        $this->request = $request;
        $this->_grid = $this->createGrid([1]);
        $os = new ObjectStream();
        $os->write($this->_grid);
        $resp = $response->withBody($os);
        return parent::__invoke($request, $resp);
    }
}
