<?php

namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categoryNameFixtures = ['Grabs', 'Rotations', 'Flips', 'Rotations désaxées', 'Slides', 'One foot', 'Old school'];

        $i = 1;

        foreach ($categoryNameFixtures as $categoryName) {
            $group = new Category();
            $group->setName($categoryName);

            $manager->persist($group);

            $this->addReference('category-'.$i , $group);
            $i++;
        }
    }
}