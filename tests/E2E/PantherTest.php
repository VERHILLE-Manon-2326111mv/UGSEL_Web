<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

class PantherTest extends PantherTestCase
{
    public function testIndexPageLoads(): void
    {
        $client = static::createPantherClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('body', '');
    }
}