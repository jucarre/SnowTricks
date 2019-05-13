<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testShowHome()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowTrick()
    {
        $client = static::createClient();

        $client->request('GET', '/trick/33');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}