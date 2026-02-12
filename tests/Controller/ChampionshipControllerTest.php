<?php

namespace App\Tests\Controller;

use App\Entity\Championship;
use App\Entity\Competition;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ChampionshipControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $championshipRepository;
    private string $path = '/championship';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->championshipRepository = $this->manager->getRepository(Championship::class);

        $events = $this->manager->getRepository(Event::class)->findAll();
        foreach ($events as $event) {
            $this->manager->remove($event);
        }

        $competitions = $this->manager->getRepository(Competition::class)->findAll();
        foreach ($competitions as $competition) {
            $this->manager->remove($competition);
        }

        foreach ($this->championshipRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Championship index');
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%s/new', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'championship[name]' => 'Testing Championship',
            'championship[type]' => 'Testing Type',
        ]);

        self::assertResponseRedirects($this->path);
        self::assertSame(1, $this->championshipRepository->count([]));
    }

    public function testShow(): void
    {
        $fixture = new Championship();
        $fixture->setName('My Championship');
        $fixture->setType('Regional');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Championship');
    }

    public function testEdit(): void
    {
        $fixture = new Championship();
        $fixture->setName('Old Name');
        $fixture->setType('Old Type');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s/edit', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Update', [
            'championship[name]' => 'Something New',
            'championship[type]' => 'Something New',
        ]);

        self::assertResponseRedirects($this->path);

        $updated = $this->championshipRepository->find($fixture->getId());

        self::assertSame('Something New', $updated->getName());
        self::assertSame('Something New', $updated->getType());
    }

    public function testRemove(): void
    {
        $fixture = new Championship();
        $fixture->setName('To Delete');
        $fixture->setType('Type');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s', $this->path, $fixture->getId()));

        $this->client->submitForm('Delete');

        self::assertResponseRedirects($this->path);
        self::assertSame(0, $this->championshipRepository->count([]));
    }
}