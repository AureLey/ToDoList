<?php

namespace Tests\App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UserControllerTest extends WebTestCase
{
    protected static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
    }

    public function testlistAction()
    {       
        $crawler = self::$client->request('GET', '/users');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());      

    }

    public function testcreateAction()
    {        
        $crawler = self::$client->request('GET', '/users/create');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());        

    }

    public function testeditAction()
    {     
        $crawler = self::$client->request(Request::METHOD_GET, '/users/1/edit'); 
        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());   
    }

}