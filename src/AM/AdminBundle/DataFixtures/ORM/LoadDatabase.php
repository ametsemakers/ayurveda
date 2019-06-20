<?php

//namespace AM\AdminBundle\DataFixtures\ORM;

//use Doctrine\Common\DataFixtures\FixtureInterface;
//use Doctrine\Common\Persistence\ObjectManager;
//use AM\AdminBundle\Entity\Category;

// Class pour remplir la bdd
//class LoadDatabase implements FixtureInterface
//{
//    public function load(ObjectManager $manager)
//    {
//        $names = [
//            'Histoire',
//            'Blog',
//            'Recettes',
//            'Conseils',
//            'Pratique'
//        ];

//        foreach ($names as $name) {
//            $category = new Category();
//            $category->setName($name);

//            $manager->persist($category);
//        }

//        $manager->flush();
//    }
//}