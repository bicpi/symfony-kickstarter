<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Acme\DemoBundle\Entity\Repository\ProductRepository")
 */
class Product
{
    const PRODUCT_OWNER = 'PRODUCT_OWNER';
    const PRODUCT_LIKE = 'PRODUCT_LIKE';

    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column()
     * @Assert\NotNull()
     * @Assert\Length(min=3)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="likedProducts")
     */
    private $likers;

    function __construct()
    {
        $this->likers = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param User $user
     *
     * @return Product
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getLikers()
    {
        return $this->likers;
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function addLiker(User $user)
    {
        $this->likers[] = $user;
    }
}
