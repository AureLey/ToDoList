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
use Symfony\Component\HttpFoundation\Request;

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

        $this->assertSame(200, self::$client->getResponse()->getStatusCode());
    }

    public function testcreateAction()
    {
        $crawler = self::$client->request('GET', '/users/create');

        $this->assertSame(200, self::$client->getResponse()->getStatusCode());
    }

    public function testeditAction()
    {
        $crawler = self::$client->request(Request::METHOD_GET, '/users/1/edit');
        $this->assertSame(200, self::$client->getResponse()->getStatusCode());
    }
}
