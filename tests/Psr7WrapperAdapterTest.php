<?php
/**
 * Created by PhpStorm.
 * User: mbunge
 * Date: 02.05.2017
 * Time: 08:27
 */

namespace Hawkbit\Presentation\Tests;
use Hawkbit\Application;
use Hawkbit\Presentation\Adapters\Adapter;
use Hawkbit\Presentation\Adapters\PlatesAdapter;
use Hawkbit\Presentation\Adapters\Psr7WrapperAdapter;
use Hawkbit\Presentation\PresentationService;
use Hawkbit\Presentation\Tests\Mocks\AppFactory;
use Zend\Diactoros\ServerRequestFactory;

class Psr7WrapperAdapterTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Application */
    private $app;

    public function testAdapter()
    {
        /** @var Psr7WrapperAdapter $adapter */
        $adapter = $this->app[Adapter::class];

        $this->assertInstanceOf(Psr7WrapperAdapter::class, $adapter);
    }

    public function testRender()
    {
        $application = $this->app;
        $application->get('/', function () use ($application) {
            return $application[PresentationService::class]->render('index', ['world' => 'World']);
        });

        $response = $application->handle(ServerRequestFactory::fromGlobals());
        $this->assertEquals('Hello World', $response->getBody()->__toString());

    }


    public function testRenderWithAdditionalResponse()
    {
        $application = $this->app;
        $application->get('/', function () use ($application) {
            $response = $application->getResponse();
            return $application[PresentationService::class]->render('index', ['world' => 'World'], $response->withHeader('API-KEY', 123));
        });

        $response = $application->handle(ServerRequestFactory::fromGlobals());
        $this->assertEquals('Hello World', $response->getBody()->__toString());
        $this->assertEquals([123], $response->getHeader('API-KEY'));

    }


    protected function setUp()
    {
        $this->app = AppFactory::create(function ($config, Application $app){
            return new Psr7WrapperAdapter(new PlatesAdapter($config), $app->getRequest(), $app->getResponse());
        });

    }


}
