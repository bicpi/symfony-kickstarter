<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 */
abstract class Selection
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $orderNb = 0;

    public function getId()
    {
        return $this->id;
    }

    public function setOrderNb($orderNb)
    {
        $this->orderNb = $orderNb;

        return $this;
    }

    public function getOrderNb()
    {
        return $this->orderNb;
    }
}
