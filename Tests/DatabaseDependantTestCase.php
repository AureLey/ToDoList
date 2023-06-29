<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DatabaseDependantTestCase extends WebTestCase
{
    protected $entityManager;

    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        DatabasePrimer::prime(static::getContainer());
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected function getEnrolledUser(array $roles = ['ROLE_USER'])
    {
        $user = new User();

        $user->setEmail('john.doe@exemple.com');
        $user->setPassword('password');
        $user->setUsername('john');
        $user->setRoles($roles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    protected function getOneTask(User $user): Task
    {
        $task = new Task();
        $task->setTitle('test Task title')
            ->setContent('Content Task')
            ->setCreatedAt(new \DateTime('now'))
            ->setUpdatedAt(new \DateTime('now'))
            ->setUser($user);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    protected function getOneTestUser()
    {
        $user = new User();

        $user->setEmail('john.test@exemple.com');
        $user->setPassword('password');
        $user->setUsername('test');
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
