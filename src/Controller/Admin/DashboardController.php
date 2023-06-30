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
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    // Injection of Repository
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    #[Route('/admin', name: 'dashboard')]
    public function adminDashboard(Request $request, PaginatorInterface $paginator): Response
    {
        // Filter Uses by Username.
        $users = $this->userRepo->findBy([], ['username' => 'ASC']);
        $userPaginate = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            5);

        return $this->render('admin/admin.html.twig', [
                'users' => $userPaginate,
                'dashboard' => true]);
    }
}
