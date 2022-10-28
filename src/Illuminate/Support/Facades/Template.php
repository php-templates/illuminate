<?php

namespace PhpTemplates\Illuminate\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \PhpTemplates\Template
 */
class Template extends Facade {
   protected static function getFacadeAccessor() {
       return 'phpt';
   }
}