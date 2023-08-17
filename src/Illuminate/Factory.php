<?php

namespace PhpTemplates\Illuminate;

use Illuminate\View\Factory as BaseFactory;

class Factory extends BaseFactory
{
    protected function viewInstance($view, $path, $data)
    {
        return new View($this, $this->getEngineFromPath($path), $view, $path, $data);
    }
}