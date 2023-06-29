<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Task;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminTaskControllerTest extends DatabaseDependantTestCase
{    
    /**
     * testDashboardHomepage; Testing ROLE ADMIN to access to dashboard
     *
     * @return void
     */
    public function testDashboardHomepage(): void
    {
        // Login.
        $this->client->loginUser($this->getEnrolledUser(['ROLE_ADMIN']));
        $this->client->request('GET', '/admin/tasks');

        // Testing redirect Route.
        $this->assertRouteSame('admin_list_tasks');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Liste des tâches');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * testAdminTaskCreation, Admin User add one task
     *
     * @return void
     */
    public function testAdminTaskCreation(): void
    {
        // Login.
        $this->client->loginUser($this->getEnrolledUser(['ROLE_ADMIN']));
        $crawler = $this->client->request('GET', 'admin/tasks/create');
        // Testing redirect Route
        $this->assertRouteSame('admin_task_create');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Créer une nouvelle tâche');
        // Testing and fill form
        $form = $crawler->selectButton('Ajouter')->form([
                                                               'task[title]' => 'testcreateTaskTitle',
                                                               'task[content]' => 'testCreateTaskContent',
        ]);

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche a été bien été ajoutée.');
    }
    
    /**
     * testAdminEditTask, Admin User Edit one Task
     *
     * @return void
     */
    public function testAdminEditTask(): void
    {
        // Init User and attach him to a task ( Voter )
        $user = $this->getEnrolledUser(['ROLE_ADMIN']);
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        $taskRepository = $this->client->getContainer()->get('doctrine')->getRepository(Task::class);
        $updatedTask = $taskRepository->findOneBy(['id' => $task->getId()]);
        // Login.
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', "/admin/tasks/{$updatedTask->getId()}/edit");

        // Testing redirect Route
        $this->assertRouteSame('admin_edit_task');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Editer une tâche');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // Testing and fill form
        $form = $crawler->filter('form[name=task]')->form([
                                                           'task[title]' => $task->getTitle(),
                                                           'task[content]' => $task->getContent(),
        ]);
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche a bien été modifié');
    }
    
    /**
     * testAdminDeleteTask, Admin User delete one Task
     *
     * @return void
     */
    public function testAdminDeleteTask(): void
    {
        // Init User and attach him to a task ( Voter )
        $user = $this->getEnrolledUser(['ROLE_ADMIN']);
        // Call function who create oneTask and link it to an user.
        $task = $this->getOneTask($user);
        $idTask = $task->getId();
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "/admin/tasks/{$idTask}/delete");

        // Testing redirect Route
        $this->assertRouteSame('admin_delete_task');
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
