<?php

namespace Acme\DemoBundle\Entity;

/**
 * Selectionable trait.
 *
 * Should be used inside entity, that needs to be selectionable.
 */
trait Selectionable
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
