<?php

namespace App\Tests\Controller;

use App\Entity\Event;
use App\Entity\Competition;
use App\Entity\Championship;
use App\Entity\Sport;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EventControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $eventRepository;
    private string $path = '/event';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->eventRepository = $this->manager->getRepository(Event::class);

        foreach ($this->eventRepository->findAll() as $object) {
            $this->manager->remove($object);
        }
        $this->manager->flush();
    }

    private function createCompetition(): Competition
    {
        $championship = new Championship();
        $championship->setName('Test Championship');

        if (method_exists($championship, 'setType')) {
            $championship->setType('Départemental');
        }

        $this->manager->persist($championship);

        $competition = new Competition();
        $competition->setName('Test Competition');

        if (method_exists($competition, 'setType')) {
            $competition->setType('Open');
        }

        $competition->setChampionship($championship);

        $this->manager->persist($competition);
        $this->manager->flush();

        return $competition;
    }

    private function createSport(): Sport
    {
        $sport = new Sport();
        $sport->setName('Football');

        if (method_exists($sport, 'setType')) {
            $sport->setType('Collectif');
        }

        $this->manager->persist($sport);
        $this->manager->flush();

        return $sport;
    }

    public function testNew(): void
    {
        $competition = $this->createCompetition();
        $sport = $this->createSport();

        $this->client->request('GET', sprintf('%s/new', $this->path));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'event[name]' => 'Testing Event',
            'event[description]' => 'Description',
            'event[startTime]' => '2026-02-12T14:00',
            'event[sport]' => $sport->getId(),
            'event[competition]' => $competition->getId(),
        ]);

        self::assertResponseRedirects($this->path);
        self::assertSame(1, $this->eventRepository->count([]));
    }

    public function testEdit(): void
    {
        $competition = $this->createCompetition();
        $sport = $this->createSport();

        $fixture = new Event();
        $fixture->setName('Initial Name');
        $fixture->setDescription('Initial Description');
        $fixture->setStartTime(new \DateTimeImmutable('2026-02-12 14:00'));
        $fixture->setSport($sport);
        $fixture->setCompetition($competition);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'event[name]' => 'Updated Name',
            'event[description]' => 'Updated Description',
            'event[startTime]' => '2026-02-13T15:00',
            'event[sport]' => $sport->getId(),
            'event[competition]' => $competition->getId(),
        ]);

        self::assertResponseRedirects($this->path);

        $updated = $this->eventRepository->find($fixture->getId());
        self::assertSame('Updated Name', $updated->getName());
        self::assertSame('Updated Description', $updated->getDescription());
    }

    public function testRemove(): void
    {
        $competition = $this->createCompetition();
        $sport = $this->createSport();

        $fixture = new Event();
        $fixture->setName('To Delete');
        $fixture->setSport($sport);
        $fixture->setCompetition($competition);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects($this->path);
        self::assertSame(0, $this->eventRepository->count([]));
    }
}