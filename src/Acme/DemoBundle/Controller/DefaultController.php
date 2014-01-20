<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template
     */
    public function homepageAction()
    {
        return [];
    }

    /**
     * @Template
     */
    public function quickLoginAction()
    {
        $request = $this->container->get('request');
        $session = $request->getSession();

        return [
            'last_username' => (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME),
            'csrf_token' => $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate'),
        ];
    }
}
