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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    
    //#[Route('/', name: 'homepage')]
    /**
     * @Route("/", name="homepage")
     */
    public function indexHomepage(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
