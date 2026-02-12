<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

class EventE2ETest extends PantherTestCase
{
    public function testCreateNewEvent(): void
    {
        $client = static::createPantherClient();

        $client->request('GET', '/event/new');

        $client->waitFor('form');

        $client->submitForm('Save', [
            'event[name]' => 'Remise des médailles UGSEL',
        ]);

        $client->waitFor('table');

        $this->assertSelectorTextContains('body', 'Remise des médailles UGSEL');

        $client->takeScreenshot('var/event_created.png');
    }
}