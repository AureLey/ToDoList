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
use Symfony\Component\BrowserKit\Request;

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

        // redirect to /login, good test is get 302 before testing redirect after upgrade of symfony
        $this->assertSame(302, self::$client->getResponse()->getStatusCode());
        // $this->assertContains('To Do List app', $crawler->filter('title')->text());
    }

    // Function testing redirection when user is not login

    // public function testIndexWithoutLogin()
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/');
    //     $this->assertResponseRedirects('/login');

    // }
}
