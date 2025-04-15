<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create roles
        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');
        $manager->persist($roleUser);

        $roleCreator = new Role();
        $roleCreator->setName('ROLE_CREATOR');
        $manager->persist($roleCreator);

        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $manager->persist($roleAdmin);

        // Create basic user
        $user = new User();
        $user->setUsername('basicuser')
            ->setEmail('user@example.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'password'))
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setBirthDate(new \DateTimeImmutable('1990-01-01'))
            ->setCreatedAt(new \DateTimeImmutable());

        $user->addRole($roleUser);
        $manager->persist($user);

        // Creator
        $creator = new User();
        $creator->setUsername('creatoruser')
            ->setEmail('creator@example.com')
            ->setPassword($this->passwordHasher->hashPassword($creator, 'password'))
            ->setFirstName('Clara')
            ->setLastName('Craft')
            ->setBirthDate(new \DateTimeImmutable('1988-03-05'))
            ->setCreatedAt(new \DateTimeImmutable());

        $creator->addRole($roleUser);
        $creator->addRole($roleCreator);
        $manager->persist($creator);

        // Admin
        $admin = new User();
        $admin->setUsername('admin')
            ->setEmail('admin@example.com')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'adminpass'))
            ->setFirstName('Ada')
            ->setLastName('Root')
            ->setBirthDate(new \DateTimeImmutable('1980-12-12'))
            ->setCreatedAt(new \DateTimeImmutable());

        $admin->addRole($roleUser);
        $admin->addRole($roleAdmin);
        $manager->persist($admin);

        $manager->flush();
    }
}
