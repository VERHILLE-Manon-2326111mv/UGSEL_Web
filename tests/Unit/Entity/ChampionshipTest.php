<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Championship;
use App\Entity\Competition;
use PHPUnit\Framework\TestCase;

class ChampionshipTest extends TestCase
{
    public function testChampionshipEntity(): void
    {
        $championship = new Championship();
        $championship->setName("National UGSEL");
        $championship->setType("team");

        $this->assertEquals("National UGSEL", $championship->getName());
        $this->assertEquals("team", $championship->getType());
    }

    public function testAddRemoveCompetition(): void
    {
        $championship = new Championship();
        $competition = new Competition();

        // Test to add
        $championship->addCompetition($competition);
        $this->assertCount(1, $championship->getCompetitions());
        $this->assertSame($championship, $competition->getChampionship());

        // Test to remove
        $championship->removeCompetition($competition);
        $this->assertCount(0, $championship->getCompetitions());
        $this->assertNull($competition->getChampionship());
    }
}