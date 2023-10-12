<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = array();
        $users[0] = new User();
        $users[0]->setUsername("steven");
        $users[0]->setRoles(array("ROLE_ADMIN"));
        $users[0]->setPassword(password_hash('adrar', PASSWORD_BCRYPT));
        $users[0]->setEmail($faker->email);
        $users[0]->setUserProfilePicture('');
        $manager->persist($users[0]);
        $users[1] = new User();
        $users[1]->setUsername("steven2");
        $users[1]->setPassword(password_hash('adrar', PASSWORD_BCRYPT));
        $users[1]->setEmail($faker->email);
        $users[1]->setUserProfilePicture('');
        $manager->persist($users[1]);
        for ($i = 2; $i < 10; $i++) {
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
