<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;;

class TagsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = array();

        for ($i = 0; $i < 23; $i++) {
            $tags[$i] = new Tags();
        }

        $tags[0]->setTagsLibelle('Guide');
        $tags[1]->setTagsLibelle('Combo');
        $tags[2]->setTagsLibelle('A.K.I');
        $tags[3]->setTagsLibelle('Blanka');
        $tags[4]->setTagsLibelle('Cammy');
        $tags[5]->setTagsLibelle('Chun-Li');
        $tags[6]->setTagsLibelle('Dee Jay');
        $tags[7]->setTagsLibelle('Dhalsim');
        $tags[8]->setTagsLibelle('E.Honda');
        $tags[9]->setTagsLibelle('Guile');
        $tags[10]->setTagsLibelle('Jamie');
        $tags[11]->setTagsLibelle('JP');
        $tags[12]->setTagsLibelle('Juri');
        $tags[13]->setTagsLibelle('Ken');
        $tags[14]->setTagsLibelle('Kimberley');
        $tags[15]->setTagsLibelle('Lily');
        $tags[16]->setTagsLibelle('Luke');
        $tags[17]->setTagsLibelle('Manon');
        $tags[18]->setTagsLibelle('Marisa');
        $tags[19]->setTagsLibelle('Rashid');
        $tags[20]->setTagsLibelle('Ryu');
        $tags[21]->setTagsLibelle('Zangief');

        for ($i = 0; $i < 22; $i++) {
            $manager->persist($tags[$i]);
        }
        $manager->flush();
    }
}
