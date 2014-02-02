<?php

namespace Acme\DemoBundle\Controller;

use Acme\DemoBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="product")
     * @Template
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('DemoBundle:Product')->getOrdered();

        return compact('products');
    }

    /**
     * @Route("/create", name="product_create")
     * @Template
     */
    public function createAction(Request $request)
    {
        $product = (new Product())->setUser($this->getUser());
        $form = $this->createForm('product_form', $product);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $msg = sprintf("Product '%s' successfully created.", $product);
            $this->get('session')->getFlashBag()->add('success', $msg);

            return $this->redirect($this->generateUrl('product'));
        }

        return [
            'product' => $product,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{id}/edit", name="product_edit")
     * @SecureParam(name="product", permissions="PRODUCT_OWNER")
     * @Template
     */
    public function editAction(Request $request, Product $product)
    {
        $form = $this->createForm('product_form', $product);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $msg = sprintf("Product '%s' successfully updated.", $product);
            $this->get('session')->getFlashBag()->add('success', $msg);

            return $this->redirect($this->generateUrl('product'));
        }

        return [
            'product' => $product,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{id}/like", name="product_like")
     * @SecureParam(name="product", permissions="PRODUCT_LIKE")
     * @Template
     */
    public function likeAction(Product $product)
    {
        $user = $this->getUser();
        $user->addLikedProduct($product);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($product);
        $em->flush();

        $msg = sprintf("Thanks for liking product '%s'.", $product);
        $this->get('session')->getFlashBag()->add('success', $msg);

        return $this->redirect($this->generateUrl('product'));
    }
}
