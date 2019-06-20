<?php

namespace AM\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AM\AdminBundle\Entity\Position;

// Class pour remplir les positions des articles
class LoadPosition implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'Theorie',
            'Histoire',
            'Blog',
            'Recettes',
            'Conseils',
            'Pratique'
        ];

        foreach ($names as $name) {
            $position = new Position();
            $position->setName($name);

            $manager->persist($position);
        }

        $manager->flush();
    }
}