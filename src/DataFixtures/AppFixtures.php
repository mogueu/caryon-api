<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create a set of 10 suppliers
        for ($i = 0; $i < 10; $i++) {
            $supplier = new Supplier();
            $supplier->setCompany('category ' . $i);
            $supplier->setRepresentative('respresentative' . $i);
            $supplier->setContact('000000000');
            $supplier->setLocation('location' . $i);
            $manager->persist($supplier);
        }


        $manager->flush();
    }
}
