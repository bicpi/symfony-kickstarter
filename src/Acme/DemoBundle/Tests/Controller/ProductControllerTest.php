<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\Tests\WebTestCase;
use Liip\FunctionalTestBundle\Annotations\QueryCount;

/**
 * @IgnoreAnnotation("depends")
 */
class ProductControllerTest extends WebTestCase
{
    public function testIndexIsSecured()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient();
        $client->request('GET', '/product/');

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

    public function testIndex()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $crawler = $client->request('GET', '/product/');

        $count = $crawler->filter('nav:contains("Usy Usix")')->count();
        $this->assertEquals(1, $count);


        $count = $crawler->filter('table:contains("Tablet")')->count();
        $this->assertEquals(1, $count);
    }

    public function testCreate()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $crawler = $client->request('GET', '/product/create');

        $count = $crawler->filter('h1:contains("Create Product")')->count();
        $this->assertEquals(1, $count);

        $form = $crawler->selectButton('Submit')->form([
            'product_form[name]' => 'Sonos'
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $count = $crawler->filter('table:contains("Sonos")')->count();
        $this->assertEquals(1, $count);
    }

    public function testEditForbiddenIfNotOwner()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $crawler = $client->request('GET', '/product/4/edit');

        $count = $crawler->filter('title:contains("403 Forbidden")')->count();
        $this->assertEquals(1, $count);
    }

    public function testEdit()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $crawler = $client->request('GET', '/product/1/edit');

        $count = $crawler->filter('h1:contains("Edit Product »TV«")')->count();
        $this->assertEquals(1, $count);

        $form = $crawler->selectButton('Submit')->form([
            'product_form[name]' => 'Smart TV'
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $count = $crawler->filter('table:contains("Smart TV")')->count();
        $this->assertEquals(1, $count);
    }

    public function testLikeForbiddenIfOwner()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $crawler = $client->request('GET', '/product/1/like');

        $count = $crawler->filter('title:contains("403 Forbidden")')->count();
        $this->assertEquals(1, $count);
    }

    public function testLike()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $client->request('GET', '/product/3/like');
        $crawler = $client->followRedirect();

        $count = $crawler->filter('table tbody tr:nth-child(4) td:nth-child(3):contains("Usy Usix")')->count();
        $this->assertEquals(1, $count);
    }

    public function testLikeForbiddenIfLikedTwice()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $client->request('GET', '/product/3/like');
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());

        $client->request('GET', '/product/3/like');
        $this->assertFalse($client->getResponse()->isSuccessful());
    }
}
