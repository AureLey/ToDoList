<?php

namespace App\Tests\Controller;

use App\Tests\Tools\ToolsWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultControllerTest extends ToolsWebTestCase
{   
    
        
    /**
     * testHomepageRedirection, Testing redirection to login route
     *
     * @return void
     */
    public function testHomepageRedirection(): void
    {
        $this->client->request(Request::METHOD_GET, '/');
        //Test Redirection when nobody is loged
        $this->assertResponseRedirects();
        //Follow Redirection
        $this->client->followRedirect();
        //Testing redirect Route
        $this->assertRouteSame('login');        

    }        

    public function testHomepageWithAuth():void
    {   
        $this->client->loginUser($this->getUserTest()); 

        $crawler = $this->client->request(Request::METHOD_GET, '/');      
        //Testing redirect Route
        $this->assertRouteSame('homepage');
        //$this->assertSelectorTextSame('title', 'To Do List app');
        $this->assertSelectorTextSame('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
       
    }

}
