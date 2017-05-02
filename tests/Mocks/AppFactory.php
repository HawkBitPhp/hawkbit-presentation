<?php
/**
 * Created by PhpStorm.
 * User: mbunge
 * Date: 02.05.2017
 * Time: 08:25
 */

namespace Hawkbit\Presentation\Tests\Mocks;


use Hawkbit\Application;
use Hawkbit\Presentation\Adapters\Adapter;
use Hawkbit\Presentation\Adapters\PlatesAdapter;
use Hawkbit\Presentation\PresentationService;

class AppFactory
{

    public static function create($adapterBuilder){
        $app = new Application(require __DIR__ . '/../assets/config.php');
        $app[Adapter::class] = call_user_func($adapterBuilder, $app->getConfig('templates'), $app);
        $app[PresentationService::class] = new PresentationService($app->getContainer());

        return $app;
    }

}