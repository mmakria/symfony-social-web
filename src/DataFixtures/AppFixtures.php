<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 200; ++$i) {

            $microPost1 = new MicroPost();
            $microPost1->setTitle('Welcome to micropost 1');
            $microPost1->setText('Micropost 1');
            $microPost1->setCreated(new \DateTime());
            $manager->persist($microPost1);

        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
