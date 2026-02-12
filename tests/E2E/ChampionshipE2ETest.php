<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

class ChampionshipE2ETest extends PantherTestCase
{
    public function testCreateNewChampionship(): void
    {
        $client = static::createPantherClient();

        $client->request('GET', '/championship/new');

        $client->waitFor('form');

        $client->submitForm('Save', [
            'championship[name]' => 'Championnat de France UGSEL',
            'championship[type]' => 'National',
        ]);

        $client->waitFor('table');

        $this->assertSelectorTextContains('body', 'Championnat de France UGSEL 🖤');

        $client->takeScreenshot('var/championship_created.png');
    }
}
