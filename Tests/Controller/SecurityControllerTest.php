<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;

class SecurityControllerTest extends DatabaseDependantTestCase
{
    public function testLogout()
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
