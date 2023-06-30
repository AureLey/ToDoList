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

namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;

class SecurityControllerTest extends DatabaseDependantTestCase
{
    /**
     * testLogout.
     */
    public function testLogout(): void
    {
        $this->client->loginUser($this->getEnrolledUser());
        $crawler = $this->client->request(Request::METHOD_GET, '/');
        // Testing redirect Route
        $this->assertRouteSame('homepage');
        // action on link.
        $link = $crawler->selectLink('Se déconnecter')->link();
        $crawler = $this->client->click($link);
        // checking route
        $this->assertRouteSame('logout');
    }
}
