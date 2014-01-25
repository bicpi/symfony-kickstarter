<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class Item
{
    use ORMBehaviors\Translatable\Translatable;
    use Selectionable;

    /**
     * @ORM\ManyToOne(targetEntity="ItemCategory")
     */
    protected $category;

    public function setCategory(ItemCategory $category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function __toString()
    {
        return (string) $this->translate()->getTitle();
    }

    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }
}
