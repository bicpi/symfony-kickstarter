<?php

namespace Acme\DemoBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\Events;

/**
 * @DI\Service
 * @DI\Tag("doctrine.event_subscriber")
 */
class DoctrineSubscriber implements EventSubscriber
{
    protected $dispatcher;

    /**
     * @DI\InjectParams
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function preFlush(PreFlushEventArgs $event)
    {
        /*
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();
        ...
        */
    }

    public function getSubscribedEvents()
    {
        return [
            Events::preFlush
        ];
    }
}
