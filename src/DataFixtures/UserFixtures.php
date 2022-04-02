<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('josep.roig');
        $user->setPassword('magicalonso123');
        $user->setName('Josep Roig Torres');
        $user->setEmail('josep.roig@students.salle.url.edu');
        $user->setRole('Intern');
        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername('alvaro.sicilia');
        $user2->setPassword('lewishamilton123');
        $user2->setName('Alvaro Sicilia');
        $user2->setEmail('alvaro.sicilia@salle.url.edu');
        $user2->setRole('Teacher');
        $manager->persist($user2);


        $manager->flush();

        $this->addReference('user', $user);
        $this->addReference('user2', $user2);
    }
}
