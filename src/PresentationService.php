<?php
/**
 * The Hawkbit Micro Framework. An advanced derivate of Proton Micro Framework
 *
 * @author Marco Bunge <marco_bunge@web.de>
 * @copyright Marco Bunge <marco_bunge@web.de>
 *
 * @license MIT
 * @since 1.0
 */

namespace Hawkbit\Presentation;


use Hawkbit\Presentation\Adapters\Adaptable;
use Hawkbit\Presentation\Adapters\Adapter;
use Hawkbit\Presentation\Adapters\PlatesAdapter;
use Psr\Container\ContainerInterface;

final class PresentationService extends Adaptable
{
    const DEFAULT_ADAPTER = PlatesAdapter::class;

    /**
     * EngineFactory constructor.
     * @param ContainerInterface $container
     * @param string $adapterClass
     */
    public function __construct(ContainerInterface $container, $adapterClass = self::DEFAULT_ADAPTER)
    {
        $this->setupAdapter($container, $adapterClass);
    }

    /**
     * Setup adapter
     *
     * @param ContainerInterface $container
     * @param $adapterClass
     */
    private function setupAdapter(ContainerInterface $container, $adapterClass){
        if($container->has(Adapter::class)){
            $adapterClass = Adapter::class;
        }

        $this->setAdapter($container->get($adapterClass));
    }
}