<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\SecurityContext;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template
     * @Cache(smaxage=60)
     */
    public function homepageAction()
    {
        return [];
    }

    /**
     * @Route("/navigation", name="navigation")
     * @Template
     * @Cache(maxage=0)
     */
    public function navigationAction()
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
