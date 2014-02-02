<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="Acme\DemoBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="FosUser")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    protected $firstName;

    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dateOfBirth;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user")
     */
    protected $products;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="likers")
     */
    protected $likedProducts;

    function __construct()
    {
        parent::__construct();
        $this->products = new ArrayCollection();
        $this->likedProducts = new ArrayCollection();
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setDateOfBirth(\DateTime $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function getFullName()
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function addLikedProduct(Product $product)
    {
        $product->addLiker($this);
        $this->likedProducts[] = $product;
    }

    public function getLikedProducts()
    {
        return $this->likedProducts;
    }

    public function __toString()
    {
        return (string) $this->getFullName();
    }
}
