<?php

use Aws\Ec2\Ec2Client;
use Symfony\Component\HttpFoundation\Request;
use App\Service;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

// configuration
require_once __DIR__ . '/../config/config.php';

// local configuration override
$localConfig = file_exists(__DIR__ . '/../config/config.local.php');
if ($localConfig) {
    include_once __DIR__ . '/../config/config.local.php';
}

// twig provider
$app->register(
    new Silex\Provider\TwigServiceProvider(),
    [
        'twig.path' => __DIR__ . '/../view',
        'twig.options' => $app['twig.options'],
    ]
);

// AWS EC2 client
$client = new Ec2Client($app['ec2']['client']);

require_once __DIR__ . '/service.php';
$service = new Service($client, $app['ec2']['dryRun']);

// ----- index -----
$app->get(
    '/',
    function () use ($app, $service) {
        $instances = $service->getInstances();

        return $app['twig']->render('page/index.twig', ['instances' => $instances]);
    }
);

// ----- start instance -----
$app->match(
    '/start',
    function (Request $request) use ($app, $service) {
        $instances = $service->getInstances();
        $token = $request->get('token');

        if (isset($instances[$token])) {
            $service->startInstance($instances[$token]['id']);
        }

        return $app->redirect('/');
    }
);

// ----- stop instance -----
$app->match(
    '/stop',
    function (Request $request) use ($app, $service) {
        $instances = $service->getInstances();
        $token = $request->get('token');

        if (isset($instances[$token])) {
            $service->stopInstance($instances[$token]['id']);
        }

        return $app->redirect('/');
    }
);

$app->run();
