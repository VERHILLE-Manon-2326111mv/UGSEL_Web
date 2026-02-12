<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Event;
use App\Entity\Sport;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testEventDates(): void
    {
        $event = new Event();
        $startDate = new \DateTimeImmutable('2024-06-01 20:00:00');

        $event->setStartTime($startDate);

        $this->assertSame($startDate, $event->getStartTime());
    }

    public function testEventSportRelation(): void
    {
        $event = new Event();
        $sport = new Sport();

        $event->setSport($sport);

        $this->assertSame($sport, $event->getSport());
    }

    public function testEventDescription(): void
    {
        $event = new Event();
        $description = "Match d'ouverture avec cérémonie";

        $event->setDescription($description);

        $this->assertEquals($description, $event->getDescription());
    }
}