<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * Encodeur de mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    

    public function load(ObjectManager $manager)
    {
        // Creation d'un objet faker
        $faker = Factory::create('fr-FR');

        // Création de nos 5 catégories
        for($c = 0; $c < 5 ; $c++){
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

        // Creation de 5 utilisateurs
        for($u = 0; $u < 5; $u++){

            // Creation d'un nouvel objet User
            $user = new User;

            // Haschage de notre mot de passe avec les paramètres de sécurité de notre $user
            //dans /config/packages/security.taml
          
            $hash = $this->encoder->encodePassword($user, "password");


            // Si premier utilisateur créé on lui donne le rôle d'admin 
            // et on lui force son adresse mail
            if ($u ===0){
                $user->setRoles(["ROLE_ADMIN"])
                    ->setEmail('admin@admin.local');
            }else {
                $user->setEmail($faker->safeEmail());
            }

            // Pour tout le monde
            $user->setPassword($hash);

            // On fait persister les données
            $manager->persist($user);
        }

        // On push le tout en BDD
        $manager->flush();
    }
}
