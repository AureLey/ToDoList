<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testUser(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $task = new Task();
        $user = new User();
        $user->setUsername('test')
            ->setEmail('testUser.test@gmail.com')
            ->setPassword('password')
            ->setRoles(['ROLE_USER']);
        $this->assertSame($user->getUsername(), $user->getUserIdentifier());
        // Testing AddTask
        $user->addTask($task);
        $this->assertCount(1, $user->getTasks());
        // Testing RemoveTask
        $user->removeTask($task);
        $this->assertCount(0, $user->getTasks());
        // Testing Entity
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(0, $errors);

        // Testing setRoles
        $user->setRoles([]);
        $this->assertSame('ROLE_USER', $user->getRoles()[0]);
    }
}
