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
            $users[$i]->setUsername($faker->userName);
            $users[$i]->setPassword($faker->password());
            $users[$i]->setEmail($faker->email);
            $users[$i]->setUserProfilePicture('');
            $manager->persist($users[$i]);
        }


        $manager->flush();
    }
}
