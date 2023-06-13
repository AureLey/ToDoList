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
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
    #[IsGranted('ROLE_ADMIN', message: 'No access! Get out!')]
    public function createUser(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('admin/create_user.html.twig', ['form' => $form->createView()]);
    }

    #[Route('admin/users/{id}/edit', name: 'admin_user_edit')]
    #[IsGranted('ROLE_ADMIN', message: 'No access! Get out!')]
    public function editUser(User $user, Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('admin/edit_user.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    #[Route('admin/users/{id}/delete', name: 'admin_user_delete')]
    #[IsGranted('ROLE_ADMIN', message: 'No access! Get out!')]
    public function deleteTask(User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a bien été supprimée.');

        return $this->redirectToRoute('dashboard');
    }
}
