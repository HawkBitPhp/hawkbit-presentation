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


abstract class Adaptable implements Adapter
{

    /** @var Adapter */
    protected $adapter;

    /**
     * @return Adapter
     */
    protected function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param Adapter $adapter
     * @return Adaptable
     */
    protected function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getEngine(){
        return $this->adapter->getEngine();
    }

    /**
     * Add preassigned template data.
     * @param  array             $data;
     * @param  null|string|array $templates;
     * @return $this
     */
    public function addData(array $data, $templates = null){
        $this->adapter->addData($data, $templates);
        return $this;
    }

    /**
     * Get all preassigned template data.
     * @param  null|string $template;
     * @return array
     */
    public function getData($template){
        return $this->adapter->getData($template);
    }

    /**
     * Check if a template exists.
     * @param  string  $name
     * @return boolean
     */
    public function exists($name)
    {
        return $this->adapter->exists($name);
    }

    /**
     * Create a new template.
     * @param  string   $name
     * @return mixed
     */
    public function make($name)
    {
        return $this->adapter->make($name);
    }

    /**
     * Create a new template and render it.
     * @param  string $name
     * @param  array $data
     * @param null $optional Optional rendering logic or implementations e. g. response for PSR7 Wrapper
     * @return string
     */
    public function render($name, array $data = array(), $optional = null)
    {
        return $this->adapter->render($name, $data, $optional);
    }

}