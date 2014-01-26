<?php

namespace Acme\DemoBundle\Controller;

use Acme\DemoBundle\Tests\WebTestCase as BaseWebTestCase;

/**
 * @IgnoreAnnotation("dataProvider")
 */
class XhrControllerTest extends BaseWebTestCase
{
    public function testResponseFormat()
    {
        $client = static::createClient();
        $client->request('GET', '/xhr/multiply/4x5.json');
        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains('content-type', 'application/json'));
    }

    /**
     * @dataProvider provideMultiplyData
     */
    public function testMultiply($first, $second)
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/xhr/multiply/%sx%s.json', $first, $second));

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(array('result' => $first * $second), $data);
    }

    public function provideMultiplyData()
    {
        return array(
            array(4, 5),
            array(3, 0),
            array('asd', 3),
        );
    }
}
