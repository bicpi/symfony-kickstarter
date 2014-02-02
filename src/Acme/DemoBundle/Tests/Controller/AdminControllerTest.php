<?php

namespace Acme\DemoBundle\Tests\Controller;

use Acme\DemoBundle\Tests\WebTestCase;
use Liip\FunctionalTestBundle\Annotations\QueryCount;

class AdminControllerTest extends WebTestCase
{
    public function testIndexIsSecured()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $crawler = $this->fetchCrawler('/admin/', 'GET', ['username' => 'user1', 'password' => 'user1'], false);

        $count = $crawler->filter('title:contains("Access Denied (403 Forbidden)")')->count();
        $this->assertEquals(1, $count);
    }

    public function testIndex()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'admin', 'password' => 'admin']);
        $crawler = $client->request('GET', '/admin/');

        $count = $crawler->filter('body:contains("This is the secured admin area of the Symfony Kickstarter.")')->count();
        $this->assertEquals(1, $count);

        $count = $crawler->filter('nav:contains("Adi Admin")')->count();
        $this->assertEquals(1, $count);

        $count = $crawler->filter('div.navigation b:contains("46")')->count();
        $this->assertEquals(1, $count);
    }

    // @todo Implement paginator test - did not word as sorting not correct. Bug in KnpPaginator?
    /*
    public function testIndexPagination()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'admin', 'password' => 'admin']);
        $crawler = $client->request('GET', '/admin/');

        // ...
    }
    */

    public function testExcel()
    {
        $this->loadFixtures(['Acme\DemoBundle\DataFixtures\ORM\LoadData']);
        $client = $this->makeClient(['username' => 'admin', 'password' => 'admin']);
        $client->request('GET', '/admin/excel');

        $true = $client->getResponse()->headers->contains(
            'content_type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        $this->assertTrue($true);

        $path = sys_get_temp_dir().'/registrations.xlsx';
        file_put_contents($path, $client->getResponse()->getContent());
        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        $registrations = $reader->load($path)->getSheet(0)->toArray();
        $this->assertEquals(47, count($registrations));
        unlink($path);
    }
}
