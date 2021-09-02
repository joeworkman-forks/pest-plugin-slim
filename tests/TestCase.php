<?php

namespace Pest\Slim\Tests;

use DI\Container;
use Pest\Slim\Traits\AppTestTrait;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

class TestCase extends BaseTestCase
{
    use AppTestTrait;

    protected function setUp(): void
    {
        // Create Container using PHP-DI
        $container = new Container();

        // Add custom request factory
        $container->set(ServerRequestFactoryInterface::class, fn () => new ServerRequestFactory());

        // Configure the application via container
        $app = AppFactory::createFromContainer($container);

        // Add Routing Middleware
        $app->addRoutingMiddleware();

        // Add Error Middleware
        $app->addErrorMiddleware(false, false, false);

        $app->any('/text', function (Request $request, Response $response, $args): Response {
            $response->getBody()->write('hello, world');

            return $response;
        });

        $app->any('/json', function (Request $request, Response $response, $args): Response {
            $response->getBody()->write('{"hello":"world"}');

            return $response;
        });

        $this->setUpApp($app);
    }
}
