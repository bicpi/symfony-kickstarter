<?php

namespace Acme\DemoBundle\DataFixtures\ORM;

use Acme\DemoBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\DemoBundle\Entity\Template;
use Acme\DemoBundle\Entity\User;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Yaml\Yaml;
use Acme\DemoBundle\Entity\Country;
use Acme\DemoBundle\Entity\ItemCategory;
use Acme\DemoBundle\Entity\Item;
use Acme\DemoBundle\Entity\Registration;

class LoadData extends AbstractFixture implements OrderedFixtureInterface
{
    protected $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadCountry();
        $this->loadItemCategory();
        $this->loadItem();
        $this->loadFOSUser();
        $this->loadRegistration();
        $this->loadProduct();
        $this->manager->flush();
    }

    public function loadCountry()
    {
        foreach ($this->loadRaw('Country') as $key => $data) {
            $entity = new Country();
            foreach ($data['title'] as $locale => $title) {
                $entity->translate($locale)->setTitle($title);
            }

            $entity->mergeNewTranslations();
            $this->addReference($key, $entity);
            $this->manager->persist($entity);
        }
    }

    public function loadItemCategory()
    {
        foreach ($this->loadRaw('ItemCategory') as $key => $data) {
            $entity = new ItemCategory();
            foreach ($data['title'] as $locale => $title) {
                $entity->translate($locale)->setTitle($title);
            }

            $entity->mergeNewTranslations();
            $this->addReference($key, $entity);
            $this->manager->persist($entity);
        }
    }

    public function loadItem()
    {
        foreach ($this->loadRaw('Item') as $key => $data) {
            $entity = new Item();
            $entity->setCategory($this->getReference($data['Category']));
            foreach ($data['title'] as $locale => $title) {
                $entity->translate($locale)->setTitle($title);
            }

            $entity->mergeNewTranslations();
            $this->addReference($key, $entity);
            $this->manager->persist($entity);
        }
    }

    public function loadFOSUser()
    {
        foreach ($this->loadRaw('User') as $key => $data) {
            $entity = new User();
            $entity->setEnabled(true);
            $entity->setFirstName($data['firstName']);
            $entity->setLastName($data['lastName']);
            $entity->setUsername($data['username']);
            $entity->setEmail($data['email']);
            $entity->setPlainPassword($data['plainPassword']);
            foreach ($data['roles'] as $role) {
                $entity->addRole($role);
            }
            $entity->setDateOfBirth(new \DateTime($data['dateOfBirth']));
            $this->addReference($key, $entity);
            $this->manager->persist($entity);
        }
    }

    public function loadRegistration()
    {
        foreach ($this->loadRaw('Registration', true) as $key => $data) {
            $entity = new Registration();
            $entity->setLocale($data['locale']);
            $entity->setName($data['name']);
            $entity->setEmail($data['email']);
            $entity->setCountry($this->getReference($data['Country']));
            foreach ($data['Items'] as $item) {
                $entity->addItem($this->getReference($item));
            }

            $this->addReference($key, $entity);
            $this->manager->persist($entity);
        }
    }

    public function loadProduct()
    {
        foreach ($this->loadRaw('Product') as $key => $data) {
            $entity = new Product();
            $entity->setName($data['name']);
            $entity->setUser($this->getReference($data['user']));
            foreach ($data['likers'] as $user) {
                $entity->addLiker($this->getReference($user));
            }
            $this->addReference($key, $entity);
            $this->manager->persist($entity);
        }
    }

    protected function loadRaw($model, $parsePhp = false)
    {
        $content = file_get_contents(sprintf(__DIR__ . '/%s.yml', $model));

        if ($parsePhp) {
            $process = new PhpProcess($content);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new \RuntimeException($process->getErrorOutput());
            }
            $content = $process->getOutput();
        }

        return Yaml::parse($content);
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
