<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Status;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory as Faker;


class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        
        $cat1 = new Category();
        $cat1->setName('Problème technique');
        $manager->persist($cat1);

        $cat2 = new Category();
        $cat2->setName('Demande d\'information');
        $manager->persist($cat2);

        
        $status1 = new Status();
        $status1->setName('Ouvert');
        $manager->persist($status1);

        $status2 = new Status();
        $status2->setName('En cours');
        $manager->persist($status2);

        $status3 = new Status();
        $status3->setName('Résolu');
        $manager->persist($status3);

        
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        
        $agent = new User();
        $agent->setEmail('agent@example.com');
        $agent->setRoles(['ROLE_AGENT']);
        $agent->setPassword($this->passwordHasher->hashPassword($agent, 'agent123'));
        $manager->persist($agent);

        
        $ticket = new Ticket();
        $ticket->setEmail('user@demo.com');
        $ticket->setTitle('Problème de connexion');
        $ticket->setDescription('Je ne peux plus accéder à mon compte, écran blanc.');
        $ticket->setCategory($cat1);
        $ticket->setStatus($status1);
        $ticket->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($ticket);

        
        $faker = Faker::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $fakeTicket = new Ticket();
            $fakeTicket->setTitle($faker->sentence(4));
            $fakeTicket->setDescription($faker->paragraph());
            $fakeTicket->setEmail($faker->safeEmail());
            $fakeTicket->setCreatedAt(new \DateTimeImmutable());
            $fakeTicket->setCategory($i % 2 === 0 ? $cat1 : $cat2);
            $fakeTicket->setStatus($status1); 
            $manager->persist($fakeTicket);
        }

        $manager->flush();
    }
}
