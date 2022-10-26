<?php

namespace PhpTemplates\Illuminate;

use PhpTemplates\Template;

class ViewFactory implements \Illuminate\Contracts\View\Engine
{
    private $shared = [];
    private $template;

    public function __construct(Template $template)
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
    public function get($path, array $data = []) 
    {
        $config = $this->template->getConfig()->getConfigFromPath($path);
        $rfilepath = '';
        foreach ($config->getPath() as $p) {
            if (strpos($path, $p) !== false) {
                $rfp = trim(str_replace([$p, '.t.php'], '', $path), '/ ');
                if (!$rfilepath || strlen($rfp) < strlen($rfilepath)) {
                    $rfilepath = $rfp;
                }
            }
        }
        
        if (!$config->isDefault()) {
            $rfilepath = $config->getName() . ':' . $rfilepath;
        }
        
        ob_start();
        $this->template->render($rfilepath, $data);
        $output = ob_get_contents();
        ob_end_clean();
        
        return $output;
    }
}