<?php

namespace Acme\DemoBundle\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @DI\Service
 */
class RequestListener
{
    /**
     * @DI\Observe(KernelEvents::REQUEST, priority = 255)
     */
    public function onKernelRequest()
    {
        // do something onKernelRequest ...
    }
}
