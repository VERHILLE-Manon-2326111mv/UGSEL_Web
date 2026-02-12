<?php

namespace App\DataFixtures;

use App\Entity\Championship;
use App\Entity\Competition;
use App\Entity\Event;
use App\Entity\Sport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 1. Un Sport unique
        $sport = new Sport();
        $sport->setName('Football');
        $sport->setType('Équipe');
        $manager->persist($sport);

        // 2. Un Championnat unique
        $championship = new Championship();
        $championship->setName('Championnat de France');
        $championship->setType('Équipe');
        $manager->persist($championship);

        // 3. Une Compétition unique liée au championnat
        $competition = new Competition();
        $competition->setName('Ligue 1');
        $competition->setChampionship($championship);
        $manager->persist($competition);

        // 4. Un Événement unique lié au sport et à la compétition
        $event = new Event();
        $event->setName('Match d\'ouverture');
        $event->setDescription('Premier match de la saison');
        $event->setStartTime(new \DateTimeImmutable('2026-09-01 21:00:00'));
        $event->setSport($sport);
        $event->setCompetition($competition);
        $manager->persist($event);

        // Envoi final
        $manager->flush();
    }
}