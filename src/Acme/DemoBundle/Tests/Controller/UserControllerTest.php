<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\Tests\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testIndexIsSecured()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient();

        $client->request('GET', '/user/');
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('title:contains("Log In")')->count()
        );
    }

    public function testIndex()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $crawler = $client->request('GET', '/user/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('nav:contains("Usy Usix")')->count()
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('h1:contains("User Area")')->count()
        );
    }
}
