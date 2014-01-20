<?php

namespace Acme\DemoBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    public function applyClientAuth($client, $username, $password)
    {
        $client->setServerParameter('PHP_AUTH_USER', $username);
        $client->setServerParameter('PHP_AUTH_PW', $password);
    }
}
