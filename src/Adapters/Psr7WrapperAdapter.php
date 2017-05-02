<?php

/**
 * The Hawkbit Micro Framework. An advanced derivate of Proton Micro Framework.
 *
 * @author Marco Bunge <marco_bunge@web.de>
 * @author Daniyal Hamid (@Designcise) <hello@designcise.com>
 * @copyright Marco Bunge <marco_bunge@web.de>
 *
 * @license MIT
 * @since 2.0
 */

namespace Hawkbit\Presentation\Adapters;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Psr7WrapperAdapter extends Adaptable
{

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(Adapter $adapter, ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->setAdapter($adapter);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Create a new template and render it.
     * @param  string $name
     * @param  array $data
     * @param ResponseInterface|null $response Enable manipulation of response, for errors or something like this
     * @return ResponseInterface
     */
    public function render($name, array $data = array(), ResponseInterface $response = null)
    {
        if (!($response instanceof ResponseInterface)) {
            $response = $this->getResponse();
        }

        $content = $this->adapter->render($name, $data);
        $response->getBody()->write($content);

        return $response;
    }

}