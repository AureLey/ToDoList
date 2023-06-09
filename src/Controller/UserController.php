<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    // Injection of Repository
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    #[Route('/users', name: 'user_list')]
    public function listAction()
    {
        return $this->render('user/list.html.twig', ['users' => $this->userRepo->findAll()]);
    }

    #[Route('/users/create', name: 'user_create')]
    public function createAction(Request $request, UserPasswordHasherInterface $encoder, ManagerRegistry $doctrine)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $password = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->userRepo->persist($user);
            $this->userRepo->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $encoder, ManagerRegistry $doctrine)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $doctrine->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
