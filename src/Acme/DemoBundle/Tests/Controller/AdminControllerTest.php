<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\Tests\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testIndexIsSecured()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient([
            'username' => 'user1',
            'password' => 'user1'
        ]);

        $crawler = $client->request('GET', '/admin/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('title:contains("Access Denied (403 Forbidden)")')->count()
        );
    }

    public function testIndex()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient([
            'username' => 'admin',
            'password' => 'admin'
        ]);

        $crawler = $client->request('GET', '/admin/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("This is the secured admin area of the Symfony Kickstarter.")')->count()
        );
    }
}
