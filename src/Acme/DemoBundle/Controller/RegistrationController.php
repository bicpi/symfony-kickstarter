<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Acme\DemoBundle\Entity\Registration;
use Acme\DemoBundle\Entity\Event\RegistrationEvent;

/**
 * @Route("/{_locale}/registration", defaults={"_locale"="en"}, requirements={"_locale"="en|de"})
 */
class RegistrationController extends Controller
{
    /**
     * @Route("/", name="registration")
     * @Template
     */
    public function registerAction(Request $request)
    {
        $registration = (new Registration())->setLocale($request->getLocale());
        $form = $this->createForm('registration_form', $registration, [
            'action' => $this->generateUrl('registration', [
                '_locale' => $request->getLocale()
            ]),
            'locale' => $request->getLocale()
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $event = new RegistrationEvent($registration);
            $this->get('event_dispatcher')->dispatch(RegistrationEvent::CREATE, $event);

            $em = $this->getDoctrine()->getManager();
            $em->persist($registration);
            $em->flush();

            $converter = $this->container->get('bicpi.html_converter');
            $html = $this->renderView(
                'DemoBundle:Registration:notification.html.twig',
                ['registration' => $registration]
            );
            $message = (new \Swift_Message())
                ->setSubject($this->container->getParameter('notification.subject'))
                ->setFrom($this->container->getParameter('notification.from'))
                ->setTo($this->container->getParameter('notification.to'))
                ->setReturnPath($this->container->getParameter('notification.return_path'))
                ->setBody($html, 'text/html')
                ->addPart($converter->convert($html), 'text/plain');
            $this->get('mailer')->send($message);

            /** @var $session Session */
            $session = $this->get('session');
            $session->set('registration_id', $registration->getId());

            return $this->redirect(
                $this->generateUrl('registration_thanks', ['_locale' => $request->getLocale()])
            );
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/thank-you", name="registration_thanks")
     * @Template
     */
    public function registerThanksAction(Request $request)
    {
        /** @var $session Session */
        $session = $this->get('session');
        $id = $session->remove('registration_id');
        $registration = $this->getDoctrine()->getRepository('DemoBundle:Registration')->find($id ? : 0);

        if (!$registration) {
            return $this->redirect($this->generateUrl('registration', array('_locale' => $request->getLocale())));
        }

        return array(
            'registration' => $registration
        );
    }
}
