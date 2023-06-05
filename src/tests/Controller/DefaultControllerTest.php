<?php

namespace Tests\AppBundle\Controller;


use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    protected static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
    }


    public function testIndex()
    {      
        $crawler = self::$client->request('GET', '/');

        //redirect to /login, good test is get 302 before testing redirect after upgrade of symfony
        $this->assertEquals(302, self::$client->getResponse()->getStatusCode());
        //$this->assertContains('To Do List app', $crawler->filter('title')->text());        

    }

    //Function testing redirection when user is not login
    
    // public function testIndexWithoutLogin()
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/');
    //     $this->assertResponseRedirects('/login');

    // }


}
