<?php
namespace Yabacon\Paystack\Tests\Helpers;

use Yabacon\Paystack\Helpers\Router;
use Yabacon\Paystack;
use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Exception\ValidationException;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $p = new Paystack('sk_');
        $this->expectException(ValidationException::class);
        $r = new Router('nonexistent', $p);
    }

    public function testSingularFor()
    {
        $this->assertEquals('transaction', Router::singularFor('transactions'));
    }

    private function availableRoutes()
    {
        $routes = [];
        $files = scandir(dirname(dirname(__DIR__)) . '/src/Paystack/Routes');
        foreach ($files as $file) {
            if ('php'===pathinfo($file, PATHINFO_EXTENSION)) {
                $routes[] = strtolower(substr($file, 0, strrpos($file, ".")));
            }
        }
        return $routes;
    }

    public function testAllAvailableRoutesAreListed()
    {
        $available = $this->availableRoutes();
        $listed = Router::$ROUTES;

        sort($available);
        sort($listed);

        $this->assertTrue($listed == $available);
    }

    public function testAllSingularsAreValidRoutes()
    {
        $available = $this->availableRoutes();
        $singulars = array_values(Router::$ROUTE_SINGULAR_LOOKUP);

        sort($available);
        sort($singulars);

        $this->assertEmpty(array_diff($singulars, $available));
    }
}
