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
        $config = $this->template->getConfig();
        if ($name && strpos($name, ':')) {
            [$cfgKey, $tmp] = explode(':', $name);
            try {
                $config = $config->find($cfgKey);
            } catch(\Exception $e) {
                $name = str_replace(':', '_', $name);
            }
        }
        
        $s = microtime(true);
        ob_start();
        //try {
        $this->template->fromFile($path, $data, [], $config, $name);
        //} catch(\Exception $e) {}
        $output = ob_get_contents();
        ob_end_clean();
        $output = (microtime(true) - $s) .'<br>'. $output;
        
        return $output;
    }
}