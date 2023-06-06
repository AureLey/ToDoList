<?php

namespace Tests\App\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    protected static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
    }

    public function testlistAction()
    {
        // self::$client->request('GET','/tasks');
        // $this->assertEquals(200, self::$client->getResponse()->getStatusCode());
    }

    public function testcreateAction()
    {        
        // $crawler = self::$client->request('GET', '/tasks/create');

        // $this->assertEquals(200, self::$client->getResponse()->getStatusCode());        

    }

    public function testeditAction()
    {     
        // $crawler = self::$client->request('GET', '/tasks/5/edit'); 
        // $this->assertEquals(200, self::$client->getResponse()->getStatusCode());   
    }
}