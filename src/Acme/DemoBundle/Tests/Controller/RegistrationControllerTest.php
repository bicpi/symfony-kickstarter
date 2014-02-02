<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\Tests\WebTestCase;
use Liip\FunctionalTestBundle\Annotations\QueryCount;

class RegistrationControllerTest extends WebTestCase
{
    public function testValidation()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/en/registration/');

        $form = $crawler->selectButton('Submit')->form([]);
        $crawler = $client->submit($form);

        $count = $crawler->filter('div.text-danger:contains("This value should not be blank.")')->count();
        $this->assertEquals(3, $count);

        $count = $crawler->filter('div.text-danger:contains("This collection should contain 1 element or more.")')->count();
        $this->assertEquals(1, $count);
    }

    public function testThankYouWithoutRegistrationRedirects()
    {
        $client = $this->makeClient();
        $client->request('GET', '/en/registration/thank-you');

        $this->assertTrue($client->getResponse()->isRedirect('/en/registration/'));
    }

    public function testRegistrationForm()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/en/registration/');

        $data = [
            'registration_form[name]' => 'Stuntman Mike',
            'registration_form[email]' => 'stuntman-mike@example.org',
            'registration_form[country]' => 3,
            'registration_form[items]' => [2, 4],
        ];
        $form = $crawler->selectButton('Submit')->form($data);

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $count = $crawler->filter('body:contains("Stuntman Mike, thank you for registering.")')->count();
        $this->assertEquals(1, $count);

        return $data;
    }

    /**
     * @depends testRegistrationForm
     */
    public function testRegistration(array $data)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('DemoBundle:Registration');
        $registration = $repo->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($data['registration_form[name]'], $registration->getName());
        $this->assertEquals($data['registration_form[email]'], $registration->getEmail());
        $this->assertEquals($data['registration_form[country]'], $registration->getCountry()->getId());
        $ids = $registration->getItems()->map(function($e){return $e->getId();})->toArray();
        $this->assertEquals($data['registration_form[items]'], $ids);
    }
}
