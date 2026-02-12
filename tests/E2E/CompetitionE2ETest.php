<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

class CompetitionE2ETest extends PantherTestCase
{
    public function testCreateNewCompetition(): void
    {
        $client = static::createPantherClient();

        $client->request('GET', '/competition/new');

        $client->waitFor('form');

        $client->submitForm('Save', [
            'competition[name]' => 'Inter-Régional Mercredi',
        ]);

        $client->waitFor('table');

        $this->assertSelectorTextContains('body', 'Inter-Régional Mercredi');

        $client->takeScreenshot('var/competition_created.png');
    }
}