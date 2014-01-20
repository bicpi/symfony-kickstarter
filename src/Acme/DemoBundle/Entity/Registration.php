<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="Acme\DemoBundle\Entity\Repository\RegistrationRepository")
 */
class Registration
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column()
     * @Assert\NotNull
     */
    protected $locale;

    /**
     * @ORM\Column()
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @ORM\Column()
     * @Assert\NotBlank
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    protected $country;

    /**
     * @ORM\ManyToMany(targetEntity="Item")
     * @ORM\JoinTable()
     * @Assert\Count(min="1")
     */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function addItem(Item $item)
    {
        $this->items->add($item);

        return $this;
    }

    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return (string) sprintf('%s, %s', $this->name, $this->country);
    }
}
