<?php

namespace App\Tests\Tools;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ToolsWebTestCase extends WebTestCase
{
        
    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function getUserTest(): User
    {
        $user = new User();
        $user->setEmail('mail@example.com')
        ->setUsername('superman')
        ->setPassword('$2y$10$gLcj4ZMWlCVUraIiAB.ma.D/E.HTavTBprRAvtEnLhZxMIWQM/7Y6')
        ->setRoles(['ROLE_USER']);
        return $user;
    }

    public function getUserAdmin(): User
    {
        $user = new User();
        $user->setEmail('master@example.com')
        ->setUsername('IronMan')
        ->setPassword('$2y$10$gLcj4ZMWlCVUraIiAB.ma.D/E.HTavTBprRAvtEnLhZxMIWQM/7Y6')
        ->setRoles(['ROLE_ADMIN']);
        return $user;
    }
}
