<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminUserControllerTest extends DatabaseDependantTestCase
{    
    /**
     * testAdminUserCreation, Admin User call new User page
     *
     * @return void
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
     * testAdminEditUser, Admin user call Edit page to modify an user, param = id
     *
     * @return void
     */
    public function testAdminEditUser(): void
    {
        $user = $this->getEnrolledUser(['ROLE_ADMIN']);
        $id = $user->getId();
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "/admin/users/{$id}/edit");

        // Testing redirect Route
        $this->assertRouteSame('admin_user_edit');
        // Testing h1 selector.
        $this->assertSelectorTextContains('h1', 'Modifier');
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * testAdminDeleteUser, Testing user deletion.
     *
     * @return void
     */
    public function testAdminDeleteUser(): void
    {
        $user = $this->getEnrolledUser(['ROLE_ADMIN']);
        // Call function who create a second testing user to delete it.
        $this->getOneTestUser();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userDelete = $userRepository->findOneByEmail('john.test@exemple.com');
        $idUserDelete = $userDelete->getId();
        // Login.
        $this->client->loginUser($user);
        $this->client->request('GET', "admin/users/{$idUserDelete}/delete");

        // Testing redirect Route
        $this->assertRouteSame('admin_user_delete');
        $this->assertResponseRedirects();
        // Follow Redirection
        $this->client->followRedirect();
        // Testing Response.
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * testFormNewuser, testing User creation form
     *
     * @return void
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
        ]);

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! L\'utilisateur a bien été ajouté.');
    }
    
    /**
     * testFormEditUser Testing Edition User form
     *
     * @return void
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
        $idUserEdit = $userEdit->getId();
        $crawler = $this->client->request('GET', "/admin/users/{$idUserEdit}/edit");
        $this->assertResponseIsSuccessful();
        // Fill edit form
        $form = $crawler->filter('form[name=user]')->form([
            'user[username]' => $userEdit->getUsername(),
            'user[password][first]' => $userEdit->getPassword(),
            'user[password][second]' => $userEdit->getPassword(),
            'user[email]' => $userEdit->getEmail(),
        ]);
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        // Testing Flash.
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! L\'utilisateur a bien été modifié');
    }
}
