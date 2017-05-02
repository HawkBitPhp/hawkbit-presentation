<?php
/**
 * Created by PhpStorm.
 * User: marco.bunge
 * Date: 18.10.2016
 * Time: 15:07
 */

namespace Hawkbit\Presentation\Tests;

use Hawkbit\Application;
use Hawkbit\Presentation\Adapters\Adapter;
use Hawkbit\Presentation\Adapters\PlatesAdapter;
use Hawkbit\Presentation\PresentationService;
use Hawkbit\Presentation\Tests\Mocks\AppFactory;
use Hawkbit\Presentation\Tests\Mocks\InjectableController;
use Zend\Diactoros\ServerRequestFactory;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Application */
    private $app;

    public function testAdapter()
    {
        $this->assertInstanceOf(PlatesAdapter::class, $this->app[Adapter::class]);
    }

    public function testIntegration()
    {
        $app = $this->app;

        /** @var PresentationService $engine */
        $engine = $app[PresentationService::class];
        $result = $engine->render('index', ['world' => 'World']);

        $this->assertInstanceOf(PresentationService::class, $engine);
        $this->assertEquals('Hello World', $result);
    }

    public function testInjectableIntegration()
    {
        $app = $this->app;
        $app->get('/', [InjectableController::class, 'getIndex']);

        $response = $app->handle(ServerRequestFactory::fromGlobals());

        $this->assertEquals('Hello World', $response->getBody()->__toString());
    }

    public function testExtendEngine()
    {
        $app = $this->app;

        /** @var PresentationService $service */
        $service = $app[PresentationService::class];
        $service->getEngine()
            ->addFolder('acme', __DIR__ . '/templates/acme')
            ->registerFunction('uppercase', function ($string) {
                return strtoupper($string);
            });

        $app->get('/', [InjectableController::class, 'getAcme']);
        $response = $app->handle(ServerRequestFactory::fromGlobals());
        $this->assertEquals('FOO BAR', $response->getBody()->__toString());
    }

    protected function setUp()
    {
        $this->app = AppFactory::create(function ($config){
            return new PlatesAdapter($config);
        });
    }

}
