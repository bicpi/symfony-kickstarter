<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\Tests\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testIndexIsSecured()
    {
        $client = static::createClient();
        $this->applyClientAuth($client, 'user', 'user');

        $crawler = $client->request('GET', '/admin/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('title:contains("Access Denied (403 Forbidden)")')->count()
        );
    }

    public function testIndex()
    {
        $client = static::createClient();
        $this->applyClientAuth($client, 'admin', 'admin');

        $crawler = $client->request('GET', '/admin/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("This is the secured admin area of the Symfony Kickstarter.")')->count()
        );
    }
}
