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

use App\Entity\Task;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends DatabaseDependantTestCase
{
    public function testTaskCreationWithAuth(): void
    {
        // Login.
        $this->client->loginUser($this->getEnrolledUser());
        $this->client->request('GET', '/tasks/create');

        // Testing redirect Route
        $this->assertRouteSame('task_create');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Créer une nouvelle tâche');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTaskCreationWithoutAuth(): void
    {
        $this->client->request('GET', '/tasks/create');

        // Test Redirection when nobody is loged
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing redirect Route
        $this->assertRouteSame('login');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTaskListUncheck(): void
    {
        // Login.
        $this->client->loginUser($this->getEnrolledUser());
        $this->client->request('GET', '/tasks');

        $this->assertRouteSame('task_list');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Liste des tâches à faire');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTaskListUncheckTestingRouteWithoutAuth(): void
    {
        $this->client->request('GET', '/tasks');
        // Test Redirection when nobody is loged
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing redirect Route
        $this->assertRouteSame('login');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTaskListDone(): void
    {
        // Login.
        $this->client->loginUser($this->getEnrolledUser());
        $this->client->request('GET', '/tasks/done');

        // Testing redirect Route
        $this->assertRouteSame('task_list_done');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Liste des tâches compléter');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTaskListDoneTestingRouteWithoutAuth(): void
    {
        $this->client->request('GET', '/tasks/done');
        // Test Redirection when nobody is loged
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing redirect Route
        $this->assertRouteSame('login');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditTask(): void
    {
        // init User and attach him to a task ( Voter )
        $user = $this->getEnrolledUser();
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        $id = $task->getId();
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "/tasks/{$id}/edit");

        // Testing redirect Route
        $this->assertRouteSame('task_edit');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Editer une tâche');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditTaskTestingRouteWithoutAuth(): void
    {
        $id = 1;
        $this->client->request('GET', '/tasks/{$id}/edit');
        // Test Redirection when nobody is loged
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing redirect Route
        $this->assertRouteSame('login');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDeleteTask()
    {
        // init User and attach him to a task ( Voter ).
        $user = $this->getEnrolledUser();
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        $id = $task->getId();
        // Login.
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', "/tasks/{$id}/delete");

        // Testing redirect Route
        $this->assertRouteSame('task_delete');
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDeleteTaskTestingRouteWithoutAuth()
    {
        $id = 1;
        $this->client->request('GET', "/tasks/{$id}/delete");
        // Test Redirection when nobody is loged
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing redirect Route
        $this->assertRouteSame('login');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTaskIsDone()
    {
        // init User and attach him to a task ( Voter )
        $user = $this->getEnrolledUser();
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        $id = $task->getId();
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "/tasks/{$id}/toggle");

        // Testing redirect Route
        $this->assertRouteSame('task_toggle');
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFormNewTask()
    {
        // Login.
        $this->client->loginUser($this->getEnrolledUser());
        $crawler = $this->client->request('GET', '/tasks/create');
        // Fill task form
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'titleTest';
        $form['task[content]'] = 'contentTest';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche a été bien été ajoutée.');
    }

    public function testFormEditTask()
    {
        // init User and attach him to a task (Voter).
        $user = $this->getEnrolledUser();
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        $id = $task->getId();
        // Login.
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', "/tasks/{$id}/edit");
        // Fill Modify Task form.
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'titleTest';
        $form['task[content]'] = 'contentTest';

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche a bien été modifiée.');
    }
}
