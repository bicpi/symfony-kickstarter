<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\DataFixtures\ORM\LoadData;
use Acme\DemoBundle\Tests\WebTestCase;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;

class LoadDataTest extends WebTestCase
{
    public function testLoadData()
    {
        $this->loadFixtures([]);
        $om = $this->getContainer()->get('doctrine')->getManager();
        $referenceRepository = new ProxyReferenceRepository($om);

        $loader = new LoadData();
        $loader->setReferenceRepository($referenceRepository);
        $loader->load($om);

        $products = $om->getRepository('DemoBundle:Product')->findAll();
        $this->assertEquals(4, count($products));

        $user = $om->getRepository('DemoBundle:User')->find(2);
        $this->assertEquals('user2@example.com', $user->getEmail());

        $item = $om->getRepository('DemoBundle:Item')->find(3);
        $this->assertEquals('Lemonade', $item->getTranslations()['en']->getTitle());
        $this->assertEquals('Limonade', $item->getTranslations()['de']->getTitle());
        $this->assertEquals('Soft Drinks', $item->getCategory()->getTranslations()['en']->getTitle());
    }
}
