<?php

namespace PhpTemplates\Illuminate;

use PhpTemplates\Template;

class TemplateEngine implements \Illuminate\Contracts\View\Engine
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
        
        if (!$rfilepath) {
            throw new \Exception("Source path '$path' is not present in any config");
        }
        
        if (!$config->isDefault()) {
            $rfilepath = $config->getName() . ':' . $rfilepath;
        }
        
        $s = microtime(true);
        ob_start();
        //try {
        $this->template->render($rfilepath, $data);
        //} catch(\Exception $e) {}
        $output = ob_get_contents();
        ob_end_clean();
        $output = (microtime(true) - $s) .'<br>'. $output;
        
        return $output;
    }
}