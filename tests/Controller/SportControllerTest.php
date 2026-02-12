<?php

namespace App\Tests\Controller;

use App\Entity\Sport;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SportControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $sportRepository;
    private string $path = '/sport';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->sportRepository = $this->manager->getRepository(Sport::class);

        foreach ($this->sportRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Sport index');
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%s/new', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'sport[name]' => 'Testing',
            'sport[type]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->sportRepository->count([]));
    }

    public function testShow(): void
    {
        $fixture = new Sport();
        $fixture->setName('My Title');
        $fixture->setType('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Sport');
    }

    public function testEdit(): void
    {
        $fixture = new Sport();
        $fixture->setName('Value');
        $fixture->setType('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'sport[name]' => 'Something New',
            'sport[type]' => 'Something New',
        ]);

        self::assertResponseRedirects($this->path);

        $fixture = $this->sportRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getType());
    }

    public function testRemove(): void
    {
        $fixture = new Sport();
        $fixture->setName('Value');
        $fixture->setType('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects($this->path);
        self::assertSame(0, $this->sportRepository->count([]));
    }
}