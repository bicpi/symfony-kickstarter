<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\Tests\WebTestCase;
use Liip\FunctionalTestBundle\Annotations\QueryCount;

class ProfileControllerTest extends WebTestCase
{
    public function testShowIsSecured()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient();

        $client->request('GET', '/profile/');
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

    public function testShow()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $crawler = $client->request('GET', '/profile/');

        $count = $crawler->filter('div.container legend:contains("Your account profile")')->count();
        $this->assertEquals(1, $count);

        $count = $crawler->filter('div.container:contains("20/12/1988")')->count();
        $this->assertEquals(1, $count);
    }

    public function testEdit()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'user1', 'password' => 'user1']);

        $crawler = $client->request('GET', '/profile/edit');

        $count = $crawler->filter('div.container legend:contains("Edit account profile")')->count();
        $this->assertEquals(1, $count);

        $value = $crawler->filter('#fos_user_profile_form_dateOfBirth')->attr('value');
        $this->assertEquals('20.12.1988', $value);

        $form = $crawler->selectButton('Update')->form([
            'fos_user_profile_form[firstName]' => 'Stuntman',
            'fos_user_profile_form[lastName]' => 'Mike',
            'fos_user_profile_form[dateOfBirth]' => '06.02.1979',
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $count = $crawler->filter('div.container:contains("06/02/1979")')->count();
        $this->assertEquals(1, $count);
    }
}
