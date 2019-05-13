<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    public function testShowHome()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowDashboard()
    {
        $client = static::createClient();

        $client->request('GET', '/user/account/dashboard');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

}