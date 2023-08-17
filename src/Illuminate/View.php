<?php

namespace PhpTemplates\Illuminate;

use Illuminate\View\View as BaseView;

class View extends BaseView
{
    protected function getContents()
    {
        return $this->engine->get($this->path, $this->gatherData(), $this->view);
    }
}