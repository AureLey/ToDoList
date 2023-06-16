<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Tools\ToolsWebTestCase;



class TaskControllerTest extends ToolsWebTestCase
{
    // public function testTaskCreation(): void
    // {   
    //     $this->client->loginUser($this->getUserTest());
    //     // $crawler = $this->client->request('GET', '/tasks/create');
        
    //     // //Testing redirect Route
    //     // $this->assertRouteSame('task_create');
    //     //$this->assertSelectorTextContains('h1','Crée une nouvelle tâche');
    //     // $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    //     //$user = new User();

    //     // $user->setEmail('john.doe@exemple.com');
    //     // $user->setPassword('password');
    //     // $user->setUsername('john');
    //     // $user->setRoles(['ROLE_USER']);

    //     $this->client->loginUser($user);

    //     $this->client->request('GET', '/tasks/create');
    //     $this->assertRouteSame('task_create');
    //     //$this->assertResponseStatusCodeSame(301);
    //     $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    //     //$this->assertSelectorTextContains('h1','Crée une nouvelle tâche');

    // }
    public function testMentor():void
    {
        $user = new User();
        $user->setEmail('john.doe@exemple.com');
        $user->setPassword('password');
        $user->setUsername('john');
        $user->setRoles(['ROLE_USER']);

        $this->client->loginUser($user);

        $this->client->request('GET','/tasks');
        //$this->assertRouteSame('homepage');
        // $this->assertResponseIsSuccessful();
    }
}