<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

class SportE2ETest extends PantherTestCase
{
    public function testCreateNewSport(): void
    {
        $client = static::createPantherClient();
        $client->request('GET', '/sport/new');

        $client->waitFor('form');

        $client->submitForm('Save', [
            'sport[name]' => 'Basketball 3x3',
            'sport[type]' => 'Collectif',
        ]);

        $client->waitFor('table');

        $this->assertSelectorTextContains('body', 'Basketball 3x3 🖤');
    }
}
