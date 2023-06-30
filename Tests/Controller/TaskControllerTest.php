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
    /**
     * testTaskCreationWithAuth, call creation task page with authentification.
     */
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

    /**
     * testTaskCreationWithoutAuth, call creation task page without authentification.
     */
    public function testTaskCreationWithoutAuth(): void
    {
        $this->client->request('GET', '/tasks/create');

        // Test Redirection when nobody is loged.
        $this->assertResponseRedirects();
        // Follow Redirection.
        $this->client->followRedirect();
        // Testing redirect Route.
        $this->assertRouteSame('login');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * testTaskListUncheck, call list of task page.
     */
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

    /**
     * testTaskListDone, call page list of task are done.
     */
    public function testTaskListDone(): void
    {
        // Login.
        $this->client->loginUser($this->getEnrolledUser());
        $this->client->request('GET', '/tasks/done');

        // Testing redirect Route.
        $this->assertRouteSame('task_list_done');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Liste des tâches compléter');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * testEditTask, call edition task page.
     */
    public function testEditTask(): void
    {
        // Init User and attach him to a task (Voter).
        $user = $this->getEnrolledUser();
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "/tasks/{$task->getId()}/edit");

        // Testing redirect Route.
        $this->assertRouteSame('task_edit');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Editer une tâche');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * testDeleteTask, call page of deletion task.
     */
    public function testDeleteTask(): void
    {
        // Init User and attach him to a task ( Voter ).
        $user = $this->getEnrolledUser();
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "/tasks/{$task->getId()}/delete");

        // Testing redirect Route.
        $this->assertRouteSame('task_delete');
        $this->assertResponseRedirects();
        // Follow Redirection.
        $this->client->followRedirect();
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * testTaskIsDone, testing isDone function.
     */
    public function testTaskIsDone(): void
    {
        // Init User and attach him to a task ( Voter ).
        $user = $this->getEnrolledUser();
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "/tasks/{$task->getId()}/toggle");

        // Testing redirect Route.
        $this->assertRouteSame('task_toggle');
        $this->assertResponseRedirects();
        // Follow Redirection.
        $this->client->followRedirect();
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * testFormNewTask testing new Task form.
     *
     * @return void
     */
    public function testFormNewTask()
    {
        // Login.
        $this->client->loginUser($this->getEnrolledUser());
        $crawler = $this->client->request('GET', '/tasks/create');
        // Fill task form.
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'titleTest';
        $form['task[content]'] = 'contentTest';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche a été bien été ajoutée.');
    }

    /**
     * testFormEditTask, testing edition task form.
     *
     * @return void
     */
    public function testFormEditTask()
    {
        // Init User and attach him to a task (Voter).
        $user = $this->getEnrolledUser();
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        // Login.
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', "/tasks/{$task->getId()}/edit");
        // Fill Modify Task form.
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'titleTest';
        $form['task[content]'] = 'contentTest';

        $this->client->submit($form);
        // Testing Task edition and fields are different to eachother.
        $this->assertNotSame($task->getTitle(), $form['task[title]']->getValue());
        $this->assertNotSame($task->getContent(), $form['task[content]']->getValue());
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche a bien été modifiée.');
    }
}
