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

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $this->createMainUser($faker, $manager);
        for ($i = 0; $i < 7; ++$i) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, 'root');
            $user->setUsername($faker->firstname())
                ->setEmail($faker->email())
                ->setRoles([])
                ->setPassword($password);
            $this->generateTask($user, $faker, $manager);
            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * generateTask, generate a random numbers of task.
     *
     * @param Faker $faker
     *
     * @return void
     */
    public function generateTask(User $user, $faker, ObjectManager $manager)
    {
        for ($j = 0; $j < rand(6, 20); ++$j) {
            $this->createTask($faker, $user, $manager);
        }
    }

    /**
     * createTask, create ONE task with random data.
     *
     * @param Faker $faker
     */
    public function createTask($faker, User $user, ObjectManager $manager): void
    {
        $task = new Task();
        $task->setTitle($faker->words(3, true))
            ->setContent($faker->words(20, true))
            ->setUser($user)
            ->setCreatedAt($faker->dateTimeBetween('-30 days', '-15 days'))
            ->setUpdatedAt($faker->dateTimeBetween('5 days', '10 days'))
            ->toggle(rand(0, 1) ? true : false);
        $manager->persist($task);
    }

    /**
     * createMainUser, Create main account ADMIN and create a random number of tasks.
     *
     * @param Faker $faker
     */
    public function createMainUser($faker, ObjectManager $manager): void
    {
        $user = new User();
        $password = $this->hasher->hashPassword($user, 'root');
        $user->setEmail('root@gmail.com')
            ->setUsername('root')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($password);
        $this->generateTask($user, $faker, $manager);
        $manager->persist($user);
    }
}
