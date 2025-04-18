<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Category;
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
        
        // Second Creator
        $creator2 = new User();
        $creator2->setUsername('jules')
            ->setEmail('jules@example.com')
            ->setPassword($this->passwordHasher->hashPassword($creator2, 'password'))
            ->setFirstName('Jules')
            ->setLastName('Martin')
            ->setBirthDate(new \DateTimeImmutable('1992-07-15'))
            ->setCreatedAt(new \DateTimeImmutable());

        $creator2->addRole($roleUser);
        $creator2->addRole($roleCreator);

        $creatorInfo2 = $creator2->getCreatorInfo();
        $creatorInfo2->setDisplayName('Jules Art')
            ->setInstagramProfile('https://instagram.com/julesart')
            ->setDescription('Artiste contemporain travaillant principalement avec le bois')
            ->setPracticalInfos('Ateliers disponibles sur demande')
            ->setCoverImage('cover_jules.jpg');

        $manager->persist($creator2);

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
        
        // Création des catégories
        $categoryDeco = new Category();
        $categoryDeco->setName('Décoration');
        $manager->persist($categoryDeco);
        
        $categoryBijoux = new Category();
        $categoryBijoux->setName('Bijoux');
        $manager->persist($categoryBijoux);
        
        $categoryArt = new Category();
        $categoryArt->setName('Art');
        $manager->persist($categoryArt);
        
        $categoryMaison = new Category();
        $categoryMaison->setName('Maison');
        $manager->persist($categoryMaison);
        
        $categoryMode = new Category();
        $categoryMode->setName('Mode');
        $manager->persist($categoryMode);
        
        // Association des catégories aux créateurs
        $categoryDeco->addCreator($creator);
        $categoryBijoux->addCreator($creator);
        $categoryMaison->addCreator($creator);
        
        $categoryArt->addCreator($creator2);
        $categoryDeco->addCreator($creator2);

        // Create a product
        $product1 = new Product();
        $product1->setName('Vase artisanal')
            ->setShortDescription('Vase en céramique fait main')
            ->setLongDescription('Vase en céramique entièrement fait à la main avec des matériaux naturels et locaux. Chaque pièce est unique.')
            ->setStock(100)
            ->setWeight(500)
            ->setWidth(20)
            ->setDepth(30)
            ->setHeight(15)
            ->setPrice(1999) // Price in cents (19.99 EUR)
            ->setShowcaseProduct(true)
            ->setStatus(ProductStatus::Published)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setCreator($creator)
            ->addCategory($categoryDeco)
            ->addCategory($categoryMaison);

        $manager->persist($product1);
        
        // Ajout d'images pour le produit 1
        $image1 = new Image();
        $image1->setFilename('vase1.jpg')
            ->setAlt('Vue principale du vase artisanal')
            ->setTitle('Vase artisanal en céramique')
            ->setPosition(0) // Image principale
            ->setProduct($product1);
        $manager->persist($image1);
        
        $image2 = new Image();
        $image2->setFilename('vase2.jpg')
            ->setAlt('Vue de côté du vase artisanal')
            ->setTitle('Détail du vase')
            ->setPosition(1)
            ->setProduct($product1);
        $manager->persist($image2);

        // Create another product
        $product2 = new Product();
        $product2->setName('Collier perles')
            ->setShortDescription('Collier en perles naturelles')
            ->setLongDescription('Collier en perles naturelles monté à la main. Ce bijou élégant saura sublimer toutes vos tenues.')
            ->setStock(50)
            ->setWeight(700)
            ->setWidth(15)
            ->setDepth(25)
            ->setHeight(20)
            ->setPrice(2999) // Price in cents (29.99 EUR)
            ->setShowcaseProduct(false)
            ->setStatus(ProductStatus::Draft)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setCreator($creator)
            ->addCategory($categoryBijoux)
            ->addCategory($categoryMode);

        $manager->persist($product2);
        
        // Ajout d'images pour le produit 2
        $image3 = new Image();
        $image3->setFilename('collier1.jpg')
            ->setAlt('Vue principale du collier en perles')
            ->setTitle('Collier en perles naturelles')
            ->setPosition(0) // Image principale
            ->setProduct($product2);
        $manager->persist($image3);
        
        // Product from second creator
        $product3 = new Product();
        $product3->setName('Sculpture bois')
            ->setShortDescription('Sculpture abstraite en bois')
            ->setLongDescription('Œuvre d\'art unique créée avec du bois de récupération. Cette sculpture apportera une touche originale à votre intérieur.')
            ->setStock(10)
            ->setWeight(1200)
            ->setWidth(40)
            ->setDepth(30)
            ->setHeight(50)
            ->setPrice(9990) // Price in cents (99.90 EUR)
            ->setShowcaseProduct(true)
            ->setStatus(ProductStatus::Published)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setCreator($creator2)
            ->addCategory($categoryArt)
            ->addCategory($categoryDeco);

        $manager->persist($product3);
        
        // Ajout d'images pour le produit 3
        $image4 = new Image();
        $image4->setFilename('sculpture1.jpg')
            ->setAlt('Vue principale de la sculpture en bois')
            ->setTitle('Sculpture abstraite en bois')
            ->setPosition(0) // Image principale
            ->setProduct($product3);
        $manager->persist($image4);
        
        $image5 = new Image();
        $image5->setFilename('sculpture2.jpg')
            ->setAlt('Vue de détail de la sculpture')
            ->setTitle('Détail de la sculpture en bois')
            ->setPosition(1)
            ->setProduct($product3);
        $manager->persist($image5);
        
        $image6 = new Image();
        $image6->setFilename('sculpture3.jpg')
            ->setAlt('Vue d\'ensemble de la sculpture')
            ->setTitle('Vue d\'ensemble de la sculpture')
            ->setPosition(2)
            ->setProduct($product3);
        $manager->persist($image6);

        $manager->flush();
    }
}
