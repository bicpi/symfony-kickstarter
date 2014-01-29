<?php

namespace Acme\DemoBundle\Entity\Event;

use Acme\DemoBundle\Entity\Product;
use FOS\UserBundle\Entity\User;

class ProductLikeEvent extends ProductEvent
{
    const LIKED = 'product.liked';

    protected $liker;

    public function __construct(Product $product, User $liker)
    {
        parent::__construct($product);
        $this->liker = $liker;
    }

    /**
     * @return User
     */
    public function getLiker()
    {
        return $this->liker;
    }
}
