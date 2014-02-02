<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\Tests\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("This is a Symfony Kickstarter.")')->count()
        );
    }
}
