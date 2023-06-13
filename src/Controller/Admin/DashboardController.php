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


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractController
{
    // Injection of Repository
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    #[Route('/admin', name: 'dashboard')]
    #[IsGranted('ROLE_ADMIN', message: 'No access! Get out!')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminTaskController',
            'users' => $this->userRepo->findAll(),
            'dashboard' => true]);
    }
}
