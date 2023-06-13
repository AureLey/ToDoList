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

namespace App\Controller\Admin;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTaskController extends AbstractController
{
    private TaskRepository $taskRepo;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, TaskRepository $taskRepo)
    {
        $this->entityManager = $entityManager;
        $this->taskRepo = $taskRepo;
    }

    #[Route('/admin/tasks', name: 'admin_list_tasks')]
    #[IsGranted('ROLE_ADMIN', message: 'No access! Get out!')]
    public function getAllTasksDashboard()
    {
        return $this->render('admin/admin.html.twig', [
            'tasks' => $this->taskRepo->findAll(),
            'dashboard' => false]);
    }

    #[Route('admin/tasks/create', name: 'admin_task_create')]
    #[IsGranted('ROLE_ADMIN', message: 'No access! Get out!')]
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

            return $this->redirectToRoute('admin_list_tasks');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/tasks/{id}/edit', name: 'admin_edit_task')]
    #[IsGranted('ROLE_ADMIN', message: 'No access! Get out!')]
    public function editTaskAdmin(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('admin_list_tasks');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/admin/tasks/{id}/delete', name: 'admin_delete_task')]
    #[IsGranted('ROLE_ADMIN', message: 'No access! Get out!')]
    public function deleteTask(Task $task): Response
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('admin_list_tasks');
    }
}
