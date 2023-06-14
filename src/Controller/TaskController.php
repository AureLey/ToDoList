<?php

declare(strict_types=1);

/*
 * This file is part of Todolist
 *
 * (c)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    // Injection of Repository
    private TaskRepository $taskRepo;
    private EntityManagerInterface $entityManager;

    public function __construct(TaskRepository $taskRepo, EntityManagerInterface $entityManager)
    {
        $this->taskRepo = $taskRepo;
        $this->entityManager = $entityManager;
    }

    #[Route('/tasks', name: 'task_list')]
    public function listTaskUncheck(Request $request, PaginatorInterface $paginator): Response
    {
        $tasks = $this->taskRepo->findBy(['isDone' => false, 'user' => $this->getUser()], ['createdAt' => 'DESC']);
        $taskPaginate = $paginator->paginate(
            $tasks,
            $request->query->getInt('page', 1),
            6);

        return $this->render('task/list.html.twig', ['tasks' => $taskPaginate]);
    }

    #[Route('/tasks/done', name: 'task_list_done')]
    public function listTaskCheck(Request $request, PaginatorInterface $paginator): Response
    {
        $tasks = $this->taskRepo->findBy(['isDone' => true, 'user' => $this->getUser()], ['createdAt' => 'DESC']);
        $taskPaginate = $paginator->paginate(
            $tasks,
            $request->query->getInt('page', 1),
            6);

        return $this->render('task/list.html.twig', ['tasks' => $taskPaginate]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createTask(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editTask(Task $task, Request $request): Response
    {
        // Check permission to delete via Voter function.
        $this->denyAccessUnlessGranted('TASK_EDIT', $task);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTask(Task $task): Response
    {
        // Check permission to delete via Voter function.
        $this->denyAccessUnlessGranted('TASK_VIEW', $task);

        $task->toggle(!$task->isDone());
        $this->entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTask(Task $task): Response
    {
        // Check permission to delete via Voter function.
        $this->denyAccessUnlessGranted('TASK_DELETE', $task);
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
