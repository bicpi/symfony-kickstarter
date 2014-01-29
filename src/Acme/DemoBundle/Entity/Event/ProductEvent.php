<?php

namespace Acme\DemoBundle\Entity\Event;

use Acme\DemoBundle\Entity\Product;
use Symfony\Component\EventDispatcher\Event;

class ProductEvent extends Event
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
