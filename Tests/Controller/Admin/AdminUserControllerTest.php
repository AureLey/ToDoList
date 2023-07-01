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

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminUserControllerTest extends DatabaseDependantTestCase
{
    /**
     * testAdminUserCreation, Admin User call new User page.
     */
    public function testAdminUserCreation(): void
    {
        // Login.
        $this->client->loginUser($this->getEnrolledUser(['ROLE_ADMIN']));
        $this->client->request('GET', 'admin/users/create');
        // Testing redirect Route
        $this->assertRouteSame('admin_user_create');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * testAdminEditUser, Admin user call Edit page to modify an user, param = id.
     */
    public function testAdminEditUser(): void
    {
        $user = $this->getEnrolledUser(['ROLE_ADMIN']);
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "/admin/users/{$user->getId()}/edit");

        // Testing redirect Route
        $this->assertRouteSame('admin_user_edit');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Modifier');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * testAdminDeleteUser, Testing user deletion.
     */
    public function testAdminDeleteUser(): void
    {
        $user = $this->getEnrolledUser(['ROLE_ADMIN']);
        // Call function who create a second testing user to delete it.
        $this->getOneTestUser();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userDelete = $userRepository->findOneByEmail('john.test@exemple.com');
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "admin/users/{$userDelete->getId()}/delete");

        // Testing redirect Route
        $this->assertRouteSame('admin_user_delete');
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * testFormNewuser, testing User creation form.
     */
    public function testFormNewuser(): void
    {
        $this->client->loginUser($this->getEnrolledUser(['ROLE_ADMIN']));
        $crawler = $this->client->request('GET', '/admin/users/create');
        // Fill new User form
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'testcreateuser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'user.test@gmail.com',
            'user[roles]' => 'ROLE_USER',
        ]);

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! L\'utilisateur a bien été ajouté.');
    }

    /**
     * testFormEditUser Testing Edition User form.
     */
    public function testFormEditUser(): void
    {
        // Init User and attach him to a task ( Voter )
        $this->client->loginUser($this->getEnrolledUser(['ROLE_ADMIN']));
        // Call function who create a second testing user to modify it.
        $this->getOneTestUser();
        // Call Repository.
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userEdit = $userRepository->findOneByEmail('john.test@exemple.com');
        $crawler = $this->client->request('GET', "/admin/users/{$userEdit->getId()}/edit");
        $this->assertResponseIsSuccessful();
        // Fill edit form
        $form = $crawler->filter('form[name=user]')->form([
            'user[username]' => 'AdminEditUserUsername',
            'user[password][first]' => 'AdminEditUserPassword',
            'user[password][second]' => 'AdminEditUserPassword',
            'user[email]' => 'AdminEditUser.email@gmail.com',
            'user[roles]' => 'ROLE_USER',
        ]);
        $this->client->submit($form);
        // Testing User edition and fields are different to eachother
        $this->assertNotSame($userEdit->getUsername(), $form['user[username]']->getValue());
        $this->assertNotSame($userEdit->getPassword(), $form['user[password][first]']->getValue());
        $this->assertNotSame($userEdit->getEmail(), $form['user[email]']->getValue());
        $this->assertEquals($userEdit->getRoles(), array($form['user[roles]']->getValue()));
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! L\'utilisateur a bien été modifié');
    }
}
