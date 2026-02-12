<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Sport;
use App\Entity\Event;
use PHPUnit\Framework\TestCase;

class SportTest extends TestCase
{
    public function testSportEntityLogic(): void
    {
        $sport = new Sport();
        $sport->setName('Football');

        $this->assertEquals('Football', $sport->getName());


        $event = new Event();
        $sport->addEvent($event);

        $this->assertCount(1, $sport->getEvents());
        $this->assertTrue($sport->getEvents()->contains($event));

        $this->assertSame($sport, $event->getSport());
    }
}