<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminDashboardTest extends DatabaseDependantTestCase
{
    /**
     * testDashboardHomepageWithoutAdminRole, testing Admin dashboard and Forbidden response.
     */
    public function testDashboardHomepageWithoutAdminRole(): void
    {
        $this->client->loginUser($this->getEnrolledUser());
        $crawler = $this->client->request('GET', '/admin');

        // Testing redirect Route
        $this->assertRouteSame('dashboard');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
