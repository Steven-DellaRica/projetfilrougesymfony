<?php

namespace App\DataFixtures;

use App\Entity\Videos;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;;

class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $videos = array();

        for ($i = 0; $i < 10; $i++) {
            $videos[$i] = new Videos();
        }

        //https://www.youtube.com/watch?v=*Video.id*

        $videos[0]->setVideoId('dQw4w9WgXcQ');
        $videos[1]->setVideoId('aAQZPBwz2CI');
        $videos[2]->setVideoId('dc90LaUcD4c');
        $videos[3]->setVideoId('3mnR8Ew7NNY');
        $videos[4]->setVideoId('6MXD33uiBhA');
        $videos[5]->setVideoId('my4a2SJQ-g8');
        $videos[6]->setVideoId('W1gfD0QwzXE');
        $videos[7]->setVideoId('kmow2QDr25w');
        $videos[8]->setVideoId('wB5J7UF7OYY');
        $videos[9]->setVideoId('gX4qMOgEmxg');

        $videos[0]->setVideoTitle('Rick Astley - Never Gonna Give You Up (Official Music Video)');
        $videos[1]->setVideoTitle('Madonna - 4 Minutes feat. Justin Timberlake & Timbaland (Official Video) [4K]');
        $videos[2]->setVideoTitle('Je FT10 le meilleur Guile Chinois !');
        $videos[3]->setVideoTitle('Je teste A.K.I !');
        $videos[4]->setVideoTitle('Je reçois des MONSTRES Français ! Kayne et Yasha !');
        $videos[5]->setVideoTitle("Le top 8 la Saltmine League s'affronte");
        $videos[6]->setVideoTitle("C'est un echec... World Warrior France #2");
        $videos[7]->setVideoTitle('Le PARRY, mauvaise mécanique ? #SF6');
        $videos[8]->setVideoTitle('Ce mec est encore sur ma route... Dernière Saltmine d...');
        $videos[9]->setVideoTitle('Challenge : Deux tournois deux personnages !');

        $videos[0]->setVideoThumbnail('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
        $videos[1]->setVideoThumbnail('https://img.youtube.com/vi/aAQZPBwz2CI/hqdefault.jpg');
        $videos[2]->setVideoThumbnail('https://img.youtube.com/vi/dc90LaUcD4c/hqdefault.jpg');
        $videos[3]->setVideoThumbnail('https://img.youtube.com/vi/3mnR8Ew7NNY/hqdefault.jpg');
        $videos[4]->setVideoThumbnail('https://img.youtube.com/vi/6MXD33uiBhA/hqdefault.jpg');
        $videos[5]->setVideoThumbnail('https://img.youtube.com/vi/my4a2SJQ-g8/hqdefault.jpg');
        $videos[6]->setVideoThumbnail('https://img.youtube.com/vi/W1gfD0QwzXE/hqdefault.jpg');
        $videos[7]->setVideoThumbnail('https://img.youtube.com/vi/kmow2QDr25w/hqdefault.jpg');
        $videos[8]->setVideoThumbnail('https://img.youtube.com/vi/wB5J7UF7OYY/hqdefault.jpg');
        $videos[9]->setVideoThumbnail('https://img.youtube.com/vi/gX4qMOgEmxg/hqdefault.jpg');

        for ($i = 0; $i < 10; $i++) {
            $videos[$i]->setVideoAuthor('M.Crimson');
            $videos[$i]->setVideoViews($faker->randomNumber(6, false));
            $videos[$i]->setVideoDate($faker->dateTimeThisDecade());
            $manager->persist($videos[$i]);
        }

        $manager->flush();
    }
}
