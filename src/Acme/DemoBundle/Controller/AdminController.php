<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DemoBundle:Registration');
        $locale = $request->getLocale();

        $pagination = $this->get('knp_paginator')->paginate(
            $repo->getFilterQb([], $locale),
            $request->query->get('page', 1),
            $this->container->getParameter('paginator.items_per_page')
        );

        return compact('pagination');
    }

    /**
     * @Route("/excel", name="admin_excel")
     */
    public function excelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $filename = sprintf(
            'Registrations.%s.xlsx',
            (new \DateTime())->format('Y-m-d')
        );

        $excel = $this->container->get('acme.excel');
        $registrations = $em->getRepository('DemoBundle:Registration')->getAll();

        return new Response($excel->registrations($registrations), 200, [
            'Pragma' => 'public',
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }
}
