<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = array();

        for($i=0; $i < 10; $i++){
            $users[$i] = new User();
        }

        // $manager->persist($product);

        $manager->flush();
    }
}
