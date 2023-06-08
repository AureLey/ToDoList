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
