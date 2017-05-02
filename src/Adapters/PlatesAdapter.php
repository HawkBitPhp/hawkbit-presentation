<?php
/**
 * The Hawkbit Micro Framework. An advanced derivate of Proton Micro Framework
 *
 * @author Marco Bunge <marco_bunge@web.de>
 * @copyright Marco Bunge <marco_bunge@web.de>
 *
 * @license MIT
 * @since 2.0
 */

namespace Hawkbit\Presentation\Adapters;


use Hawkbit\Configuration;
use League\Plates\Engine;
use League\Plates\Template\Template;

class PlatesAdapter implements Adapter
{

    /** @var Engine */
    private $engine;

    /**
     * EngineFactory constructor.
     * @param array|Configuration $templates
     */
    public function __construct($templates)
    {

        // determine default
        if(isset($templates['default'])){
            $default = $templates['default'];
            unset($templates['default']);
        }else{
            $default = array_shift($templates);
        }

        // build engine
        $engine = new Engine($default);
        foreach ($templates as $name => $template) {
            $engine->addFolder($name, $template, false);
        }

        $this->engine = $engine;
    }

    public function getEngine(){
        return $this->engine;
    }

    /**
     * Add preassigned template data.
     * @param  array             $data;
     * @param  null|string|array $templates;
     * @return $this
     */
    public function addData(array $data, $templates = null){
        $this->engine->addData($data, $templates);
        return $this;
    }

    /**
     * Get all preassigned template data.
     * @param  null|string $template;
     * @return array
     */
    public function getData($template){
        return $this->engine->getData($template);
    }

    /**
     * Check if a template exists.
     * @param  string  $name
     * @return boolean
     */
    public function exists($name)
    {
        return $this->engine->exists($name);
    }

    /**
     * Create a new template.
     * @param  string   $name
     * @return Template
     */
    public function make($name)
    {
        return $this->engine->make($name);
    }

    /**
     * Create a new template and render it.
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render($name, array $data = array())
    {
        return $this->engine->render($name, $data);
    }
}