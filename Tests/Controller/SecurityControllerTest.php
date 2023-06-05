<?php

namespace Tests\App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SecurityControllerTest extends WebTestCase
{
    protected static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
    }
    public function testLoginAction()
    {       
        $crawler = self::$client->request('GET', '/login');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());      

    }
}