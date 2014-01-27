<?php

namespace Acme\DemoBundle\Entity\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpKernel\KernelEvents;
use Acme\DemoBundle\Entity\Event\RegistrationEvent;
#use Mailer

/**
 * @DI\Service
 */
class RegistrationListener
{
    private $mailer;

    /**
     * @DI\InjectParams({
     *     "mailer" = @DI\Inject
     * })
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @DI\Observe(RegistrationEvent::CREATE)
     */
    public function onRegistrationCreate(RegistrationEvent $event)
    {
        $registration = $event->getRegistration();

        $message = (new \Swift_Message())
            ->setSubject('Event listening')
            ->setFrom('p.rieber@gmail.com')
            ->setTo('p.rieber@gmail.com')
            ->setReturnPath('p.rieber@gmail.com')
            ->setBody('Hei ho event listening');
        $this->mailer->send($message);
    }
}
