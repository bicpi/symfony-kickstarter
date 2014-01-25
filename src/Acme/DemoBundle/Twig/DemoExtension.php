<?php

namespace Acme\DemoBundle\Twig;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("acme.twig.default_extension")
 * @DI\Tag("twig.extension")
 */
class DemoExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            'contains' => new \Twig_Function_Method($this, 'contains'),
        ];
    }

    public function contains($value, $needle)
    {
        if (function_exists('mb_get_info')) {
            return false !== mb_strpos($value, $needle);
        } else {
            return false !== strpos($value, $needle);
        }
    }

    public function getName()
    {
        return 'acme_extension';
    }
}
