<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\Tests\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testIndexIsSecured()
    {
        $client = static::createClient();


        $crawler = $client->request('GET', '/user/');
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('title:contains("Log In")')->count()
        );
    }

    public function testIndex()
    {
        $client = static::createClient();
        $this->applyClientAuth($client, 'user', 'user');

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
