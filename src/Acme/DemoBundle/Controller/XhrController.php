<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * XHR controller.
 *
 * @Route("/xhr")
 */
class XhrController extends Controller
{
    /**
     * @Route("/multiply/{first}x{second}.json", name="xhr_multiply", options={"expose"=true})
     */
    public function multiplyAction(Request $request, $first = 0, $second = 0)
    {
        $result = [
            'result' => (int) $first * (int) $second
        ];

        return new JsonResponse($result);
    }
}
