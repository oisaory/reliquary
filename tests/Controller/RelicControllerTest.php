<?php

namespace App\Tests\Controller;

use App\Tests\TestCase\ExtendedWebTestCase;

class RelicControllerTest extends ExtendedWebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/relic');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Relics');
    }
}
