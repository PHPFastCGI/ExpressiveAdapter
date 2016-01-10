<?php

namespace PHPFastCGI\Adapter\Expressive;

use PHPFastCGI\FastCGIDaemon\Http\RequestInterface;
use PHPFastCGI\FastCGIDaemon\KernelInterface;
use Zend\Diactoros\Response;
use Zend\Expressive\Application;

/**
 * Wraps a Zend Expressive application object as an implementation of the kernel
 * interface.
 */
class ApplicationWrapper implements KernelInterface
{
    /**
     * @var Application
     */
    private $application;

    /**
     * Constructor.
     * 
     * @param Application $application The Zend Expressive application object to wrap
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request)
    {
        return call_user_func($this->application, $request->getServerRequest(), new Response());
    }
}
