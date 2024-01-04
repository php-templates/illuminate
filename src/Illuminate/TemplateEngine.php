<?php

namespace PhpTemplates\Illuminate;

use PhpTemplates\PhpTemplates;

class TemplateEngine implements \Illuminate\Contracts\View\Engine
{
    private $shared = [];
    private $template;

    public function __construct(PhpTemplates $template)
    {
        $this->template = $template;
    }
    
    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array  $data
     * @return string
     */
    public function get($path, array $data = [], $name = null) 
    {
        $config = $this->template->config();
        if ($name && strpos($name, ':')) {
            [$cfgKey, $tmp] = explode(':', $name);
            try {
                $config = $config->find($cfgKey);
            } catch(\Exception $e) {
                $name = str_replace(':', '__', $name);
            }
        }

        $s = microtime(true);
        $template = $this->template->fromPath(str_replace('.', '/', $name), $data, [], $config, $name);
        ob_start();
        try {
            $template->render();
        } catch(\Exception $e) {
            ob_end_clean();
            throw $e;
        }
        
        $output = ob_get_contents();
        ob_end_clean();
        //dump(microtime(true) - $s);

        return $output;
    }
}

function xxx() {
    ?>aaaaa<?php
}