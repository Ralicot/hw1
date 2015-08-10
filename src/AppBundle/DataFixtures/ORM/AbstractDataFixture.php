<?php
namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
abstract class AbstractDataFixture extends AbstractFixture
    implements OrderedFixtureInterface
{
    protected $manager;
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->preLoad();
        $this->createAndPersistData();
        $this->manager->flush();
    }
    protected function preLoad()
    {
    }
    abstract protected function createAndPersistData();
}