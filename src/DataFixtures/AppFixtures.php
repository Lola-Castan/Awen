<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Product;
use App\Enum\ProductStatus;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
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

        $creatorInfo = $creator->getCreatorInfo();
        $creatorInfo->setDisplayName('Clara C.')
            ->setInstagramProfile('https://instagram.com/claracraft')
            ->setFacebookProfile('https://facebook.com/claracraft')
            ->setPinterestProfile('https://pinterest.com/claracraft')
            ->setDescription('Créatrice passionnée par le DIY')
            ->setPracticalInfos('Livraison sous 5 jours ouvrés')
            ->setCoverImage('cover_clara.jpg');

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

        // Create a product
        $product1 = new Product();
        $product1->setName('Product 1')
            ->setShortDescription('Short description of product 1')
            ->setLongDescription('Long description for product 1')
            ->setStock(100)
            ->setWeight(500)
            ->setWidth(20)
            ->setDepth(30)
            ->setHeight(15)
            ->setPrice(1999) // Price in cents (19.99 EUR)
            ->setShowcaseProduct(true)
            ->setStatus(ProductStatus::Published)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setCreator($creator);

        $manager->persist($product1);

        // Create another product
        $product2 = new Product();
        $product2->setName('Product 2')
            ->setShortDescription('Short description of product 2')
            ->setLongDescription('Long description for product 2')
            ->setStock(50)
            ->setWeight(700)
            ->setWidth(15)
            ->setDepth(25)
            ->setHeight(20)
            ->setPrice(2999) // Price in cents (29.99 EUR)
            ->setShowcaseProduct(false)
            ->setStatus(ProductStatus::Draft)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setCreator($creator);

        $manager->persist($product2);

        $manager->flush();
    }
}
