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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    // Injection of Repository
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    #[Route('/admin', name: 'dashboard')]
    public function adminDashboard()
    {
        return $this->render('security/admin.html.twig', [
            'controller_name' => 'AdminTaskController',
            'users' => $this->userRepo->findAll(),
            'dashboard' => true]);
    }
}
