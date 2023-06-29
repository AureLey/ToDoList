<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends DatabaseDependantTestCase
{    
    /**
     * testTaskCreationWithAuth, call creation task page with authentification
     *
     * @return void
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
     * testTaskCreationWithoutAuth, call creation task page without authentification
     *
     * @return void
     */
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
    
    /**
     * testTaskListUncheck, call list of task page
     *
     * @return void
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
     * testTaskListUncheckTestingRouteWithoutAuth, call list of task page without authentification
     *
     * @return void
     */
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
    
    /**
     * testTaskListDone, call page list of task are done
     *
     * @return void
     */
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
    
    /**
     * testTaskListDoneTestingRouteWithoutAuth, call list of task without authentification
     *
     * @return void
     */
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
    
    /**
     * testEditTask, call edition task page
     *
     * @return void
     */
    public function testEditTask(): void
    {
        // Init User and attach him to a task ( Voter )
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
    
    /**
     * testEditTaskTestingRouteWithoutAuth , call edition task page without authentification
     *
     * @return void
     */
    public function testEditTaskTestingRouteWithoutAuth(): void
    {
        $idtask = 1;
        $this->client->request('GET', "/tasks/{$idtask}/edit");
        // Test Redirection when nobody is loged
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing redirect Route
        $this->assertRouteSame('login');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * testDeleteTask, call page of deletion task
     *
     * @return void
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

        // Testing redirect Route
        $this->assertRouteSame('task_delete');
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * testDeleteTaskTestingRouteWithoutAuth, call page of deletion task without authentification
     *
     * @return void
     */
    public function testDeleteTaskTestingRouteWithoutAuth(): void
    {
        $idTask = 1;
        $this->client->request('GET', "/tasks/{$idTask}/delete");
        // Test Redirection when nobody is loged
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing redirect Route
        $this->assertRouteSame('login');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * testTaskIsDone, testing isDone function
     *
     * @return void
     */
    public function testTaskIsDone(): void
    {
        // Init User and attach him to a task ( Voter )
        $user = $this->getEnrolledUser();
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "/tasks/{$task->getId()}/toggle");

        // Testing redirect Route
        $this->assertRouteSame('task_toggle');
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * testFormNewTask testing new Task form
     *
     * @return void
     */
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
    
    /**
     * testFormEditTask, testing edition task form
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
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche a bien été modifiée.');
    }
}
