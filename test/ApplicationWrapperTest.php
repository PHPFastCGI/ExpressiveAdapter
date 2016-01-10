<?php

namespace PHPFastCGI\Test\Expressive;

use PHPFastCGI\Adapter\Expressive\ApplicationWrapper;
use PHPFastCGI\FastCGIDaemon\Http\Request;
use Zend\Expressive\AppFactory;

/**
 * Tests the application wrapper.
 */
class ApplicationWrapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that requests are passed through the wrapper correctly.
     */
    public function testHandleRequest()
    {
        // Create the Expressive app
        $app = AppFactory::create();

        // Add a simple route to the app
        $app->get('/test', function ($request, $response, $next) {
            $response->getBody()->write('Hello, World!');
            return $response;
        });

        // Create a kernel for the FastCGI daemon using the Expressive app
        $kernel = new ApplicationWrapper($app);

        // Create a request to test the route set up for the app
        $stream = fopen('php://temp', 'r');
        $request = new Request(['REQUEST_URI' => '/test'], $stream);

        // Get the response from the kernel that is wrapping the app
        $response = $kernel->handleRequest($request);

        // Check that the app has been wrapper properly by comparing to expected response
        $this->assertSame('Hello, World!', (string) $response->getBody());

        fclose($stream);
    }
}
