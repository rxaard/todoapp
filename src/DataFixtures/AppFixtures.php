<?php

namespace App\DataFixtures;

use App\Entity\Task;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Creation d'un objet faker
        $faker = Factory::create('fr-FR');

        // Creation entre 15 et 30 tâches aléatoires
        for ($t = 0; $t < mt_rand(15, 30); $t++){
            $task = new Task;

            //On nourrit l'objet Task
            $task->setName($faker->sentence(6))
                ->setDescription($faker->paragraph(3))
                ->setCreatedAt(new DateTime())
                ->setDueAt($faker->dateTimeBetween('now','6 month'));

        // On fait persister les données
            $manager->persist($task);
        }

        $manager->flush();
    }
}
