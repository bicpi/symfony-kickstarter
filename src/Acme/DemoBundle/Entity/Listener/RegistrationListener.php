<?php

namespace Acme\DemoBundle\Entity\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Acme\DemoBundle\Entity\Event\RegistrationEvent;
use Acme\DemoBundle\Service\Mailer;

/**
 * @DI\Service
 */
class RegistrationListener
{
    private $mailer;

    private $adminEmail;

    /**
     * @DI\InjectParams({
     *     "mailer" = @DI\Inject("acme.mailer"),
     *     "adminEmail" = @DI\Inject("%admin_email%")
     * })
     */
    public function __construct(Mailer $mailer, $adminEmail)
    {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
    }

    /**
     * @DI\Observe(RegistrationEvent::CREATED)
     */
    public function onRegistrationCreated(RegistrationEvent $event)
    {
        $this->mailer->send(
            (new \Swift_Message())->setTo($this->adminEmail),
            'DemoBundle:Registration:notification.html.twig',
            ['registration' => $event->getRegistration()]
        );
    }
}
