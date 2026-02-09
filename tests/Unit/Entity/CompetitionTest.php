<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Competition;
use App\Entity\Event;
use PHPUnit\Framework\TestCase;

class CompetitionTest extends TestCase
{
    public function testCompetitionName(): void
    {
        $competition = new Competition();
        $competition->setName("Ligue des Champions");

        $this->assertEquals("Ligue des Champions", $competition->getName());
    }

    public function testAddRemoveEvent(): void
    {
        $competition = new Competition();
        $event = new Event();

        //test to Add
        $competition->addEvent($event);
        $this->assertCount(1, $competition->getEvents());
        $this->assertSame($competition, $event->getCompetition());

        // Test To remove
        $competition->removeEvent($event);
        $this->assertCount(0, $competition->getEvents());
    }
}