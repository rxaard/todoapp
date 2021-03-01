<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks/listing", name="task")
     */
    public function taskListing(): Response
    {

        // On va chercher par Doctrine le repository de nos Tasks
        $repository = $this->getDoctrine()->getRepository(Task::class);

        // dans ce repository nous récupérons toutes les données
        $tasks = $repository->findAll();

        // afficher les données dans le var_dumper
        // dd($tasks);

        // 

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}
