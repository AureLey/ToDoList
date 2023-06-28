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

namespace App\Tests\Controller;

use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends DatabaseDependantTestCase
{
    /**
     * testHomepage, Testing Homepage.
     */
    public function testIndexHomepage(): void
    {
        $this->client->request(Request::METHOD_GET, '/');
        // Test Redirection when nobody is loged
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing redirect Route
        $this->assertRouteSame('login');
    }

    public function testHomepageWithAuth(): void
    {
        $this->client->loginUser($this->getEnrolledUser());
        $crawler = $this->client->request(Request::METHOD_GET, '/');
        // Testing redirect Route
        $this->assertRouteSame('homepage');
        // Testing Response is success.
        $this->assertResponseIsSuccessful();
    }

    public function testHomepageWithWrongAuth(): void
    {
        $this->client->request(Request::METHOD_GET, '/');

        // Testing Response Code.
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // Testing redirection.
        $this->assertResponseRedirects();

        // Follow Redirection.
        $this->client->followRedirect();

        // Testing Response Code after redirect
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Testing route
        $this->assertRouteSame('login');
    }

    public function testHomepageFrontElementWithAut()
    {
        $this->client->loginUser($this->getEnrolledUser());
        $crawler = $this->client->request(Request::METHOD_GET, '/');
        // Testing redirect Route
        $this->assertRouteSame('homepage');

        // Testing Selector H1
        $this->assertSelectorExists('h1');

        // Testion titles.
        $this->assertSelectorTextSame('title', 'To Do List app');

        // Test Link Se Déconnecter /Logout
        $logoutBtn = $crawler->filter('.btn-danger');
        $logoutRoute = $this->client->getContainer()->get('router')->generate('logout');
        $this->assertSame($logoutRoute, $logoutBtn->filter('a')->attr('href'));

        // Test Link Nouvelle Tâche /tasks/create
        $logoutBtn = $crawler->filter('.btn-success');
        $logoutRoute = $this->client->getContainer()->get('router')->generate('task_create');
        $this->assertSame($logoutRoute, $logoutBtn->filter('a')->attr('href'));

        // Test Link Consulter la liste à faire /tasks
        $logoutBtn = $crawler->filter('.btn-info');
        $logoutRoute = $this->client->getContainer()->get('router')->generate('task_list');
        $this->assertSame($logoutRoute, $logoutBtn->filter('a')->attr('href'));

        // Test Link Consulter la liste terminées /tasks/done
        $logoutBtn = $crawler->filter('.btn-primary');
        $logoutRoute = $this->client->getContainer()->get('router')->generate('task_list_done');
        $this->assertSame($logoutRoute, $logoutBtn->filter('a')->attr('href'));
    }
}
