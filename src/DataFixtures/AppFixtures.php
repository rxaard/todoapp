<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Creation d'un objet faker
        $faker = Factory::create('fr-FR');

        // Création de nos 5 catégories
        for($c = 0; $c <=5 ; $c++){
            // Création d'un nouvel objet tag
            $tag = new Tag;

            // On ajoute un nom à notre catégorie
            $tag->setName($faker->colorName());

            //On fait persisté les données
            $manager->persist($tag);
        }
        // On flush les catégories en BDD
        $manager->flush();

        // Récuperer les catégories créés
        $allTags = $manager->getRepository(Tag::class)->findAll();


        // Creation entre 15 et 30 tâches aléatoires
        for ($t = 0; $t < mt_rand(15, 30); $t++){
            $task = new Task;

            //On nourrit l'objet Task
            $task->setName($faker->sentence(6))
                ->setDescription($faker->paragraph(3))
                ->setCreatedAt(new DateTime())
                ->setDueAt($faker->dateTimeBetween('now','6 month'))
                ->setTag($faker->randomElement($allTags));

        // On fait persister les données
            $manager->persist($task);
        }

        $manager->flush();
    }
}
