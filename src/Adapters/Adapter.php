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


interface Adapter
{
    /**
     * @return mixed
     */
    public function getEngine();

    /**
     * Add preassigned template data.
     * @param  array             $data;
     * @param  null|string|array $templates;
     * @return $this
     */
    public function addData(array $data, $templates = null);

    /**
     * Get all preassigned template data.
     * @param  null|string $template;
     * @return array
     */
    public function getData($template);

    /**
     * Check if a template exists.
     * @param  string  $name
     * @return boolean
     */
    public function exists($name);

    /**
     * Create a new template.
     * @param  string   $name
     * @return mixed
     */
    public function make($name);

    /**
     * Create a new template and render it.
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render($name, array $data = array());
}