<?php

namespace Acme\DemoBundle\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Matcher\Dumper\ApacheMatcherDumper;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Acme\DemoBundle\Entity\Product;

/**
 * @DI\Service(public=false)
 * @DI\Tag("security.voter")
 */
class ProductVoter implements VoterInterface
{
    /**
     * Checks if the voter supports the given attribute.
     *
     * @param string $attribute An attribute
     *
     * @return Boolean true if this Voter supports the attribute, false otherwise
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, [
            Product::PRODUCT_OWNER,
            Product::PRODUCT_LIKE
        ]);
    }

    /**
     * Checks if the voter supports the given class.
     *
     * @param string $class A class name
     *
     * @return Boolean true if this Voter can process the class
     */
    public function supportsClass($class)
    {
        return $class === 'Acme\DemoBundle\Entity\Product';
    }

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token A TokenInterface instance
     * @param object $object The object to secure
     * @param array $attributes An array of attributes associated with the method being invoked
     *
     * @return integer either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        if (!$this->supportsClass(get_class($object))) {
            return $result;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $result = VoterInterface::ACCESS_DENIED;

            switch ($attribute) {
                case Product::PRODUCT_OWNER:
                    if ($token->getUser() === $object->getUser()) {
                        return VoterInterface::ACCESS_GRANTED;
                    }
                    break;
                case Product::PRODUCT_LIKE:
                    #echo '<pre>'; var_dump($object->getLikers()); exit;
                    if (
                        $token->getUser() !== $object->getUser()
                        &&
                        !$object->getLikers()->contains($token->getUser())
                    ) {
                        return VoterInterface::ACCESS_GRANTED;
                    }
                    break;
            }
        }

        return $result;
    }

}
