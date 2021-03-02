<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks/listing", name="tasks_listing")
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

    /**
     * @Route("/tasks/create", name="tasks_create")
     *
     * @param Request $request
     * @return Response
     */
    public function createTask(Request $request): Response {
        // On créé un nouvel objet Task
        $task = new Task;

        // on nourri notre objet task avec nos données calculées
        $task->setCreatedAt(new \DateTime());

        $form = $this->createform(TaskType::class, $task, []);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form-> isValid()){
            $task->setName($form['name']->getData())
            ->setDescription($form['description']->getData())
            ->setDueAt($form['dueAt']->getData())
            ->setTag($form['tag']->getData());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('tasks_listing');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }
}
