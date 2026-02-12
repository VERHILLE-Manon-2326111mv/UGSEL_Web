<?php

namespace App\Tests\Controller;

use App\Entity\Competition;
use App\Entity\Championship;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CompetitionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $competitionRepository;
    private string $path = '/competition';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->competitionRepository = $this->manager->getRepository(Competition::class);

        foreach ($this->competitionRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    private function createChampionship(): Championship
    {
        $championship = new Championship();
        $championship->setName('Test Championship');
        $championship->setType('Test Type');

        $this->manager->persist($championship);
        $this->manager->flush();

        return $championship;
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Competition index');
    }

    public function testNew(): void
    {
        $championship = $this->createChampionship();

        $this->client->request('GET', sprintf('%s/new', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'competition[name]' => 'Testing',
            'competition[championship]' => $championship->getId(),
        ]);

        self::assertResponseRedirects($this->path);
        self::assertSame(1, $this->competitionRepository->count([]));
    }

    public function testShow(): void
    {
        $championship = $this->createChampionship();

        $fixture = new Competition();
        $fixture->setName('My Title');
        $fixture->setChampionship($championship);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Competition');
    }

    public function testEdit(): void
    {
        $championship = $this->createChampionship();

        $fixture = new Competition();
        $fixture->setName('Value');
        $fixture->setChampionship($championship);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s/edit', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Update', [
            'competition[name]' => 'Something New',
            'competition[championship]' => $championship->getId(),
        ]);

        self::assertResponseRedirects($this->path);

        $updated = $this->competitionRepository->findAll();

        self::assertSame('Something New', $updated[0]->getName());
        self::assertSame($championship->getId(), $updated[0]->getChampionship()->getId());
    }

    public function testRemove(): void
    {
        $championship = $this->createChampionship();

        $fixture = new Competition();
        $fixture->setName('Value');
        $fixture->setChampionship($championship);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Delete');

        self::assertResponseRedirects($this->path);
        self::assertSame(0, $this->competitionRepository->count([]));
    }
}
