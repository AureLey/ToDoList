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

use App\Entity\User;
use App\Form\UserType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class AdminUserController extends AbstractController
{
    // Injection of Repository
    private UserRepository $userRepo;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepo, EntityManagerInterface $entityManager)
    {
        $this->userRepo = $userRepo;
        $this->entityManager = $entityManager;
    }

    #[Route('admin/users/create', name: 'admin_user_create')]
    public function createUser(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->userRepo->save($user, true);

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('admin/create_user.html.twig', ['form' => $form->createView()]);
    }

    #[Route('admin/users/{id}/edit', name: 'admin_user_edit')]
    public function editUser(User $user, Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->userRepo->save($user, true);

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('admin/edit_user.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    #[Route('admin/users/{id}/delete', name: 'admin_user_delete')]
    public function deleteUser(User $user, TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findBy(['user' => $user->getId()]);
        foreach ($tasks as $key => $task) {
            $task->setUser(null);
            if ($key === array_key_last($tasks)) {
                $taskRepository->save($task, true);
            } else {
                $taskRepository->save($task);
            }
        }
        $this->userRepo->remove($user, true);

        $this->addFlash('success', 'L\'utilisateur a bien été supprimée.');

        return $this->redirectToRoute('dashboard');
    }
}
