<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    
    public function testHomepageRedirection(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        //$this->assertResponseIsSuccessful();
        $this->assertResponseRedirects();
    }
}
