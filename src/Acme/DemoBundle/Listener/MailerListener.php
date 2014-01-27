<?php

namespace Acme\DemoBundle\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Acme\DemoBundle\Entity\Event\RegistrationEvent;

/**
 * @DI\Service
 */
class MailerListener
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
     * @DI\Observe(RegistrationEvent::CREATE, priority = 255)
     */
    public function onRegistrationCreate(RegistrationEvent $event)
    {
        // send Mail
    }
}
