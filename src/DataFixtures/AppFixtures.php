<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Event;
use App\Entity\Product;
use App\Entity\Category;
use App\Enum\ProductStatus;
use App\Enum\EventStatus;
use App\Enum\EventUserStatus;
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
        
        // Création des images (indépendantes des produits)
        $images = [];
        
        $image1 = new Image();
        $image1->setFilename('vase1.jpg')
            ->setAlt('Vue principale du vase artisanal')
            ->setTitle('Vase artisanal en céramique')
            ->setPosition(0);
        $manager->persist($image1);
        $images[] = $image1;
        
        $image2 = new Image();
        $image2->setFilename('vase2.jpg')
            ->setAlt('Vue de côté du vase artisanal')
            ->setTitle('Détail du vase')
            ->setPosition(1);
        $manager->persist($image2);
        $images[] = $image2;
        
        $image3 = new Image();
        $image3->setFilename('collier1.jpg')
            ->setAlt('Vue principale du collier en perles')
            ->setTitle('Collier en perles naturelles')
            ->setPosition(0);
        $manager->persist($image3);
        $images[] = $image3;
        
        $image4 = new Image();
        $image4->setFilename('sculpture1.jpg')
            ->setAlt('Vue principale de la sculpture en bois')
            ->setTitle('Sculpture abstraite en bois')
            ->setPosition(0);
        $manager->persist($image4);
        $images[] = $image4;
        
        $image5 = new Image();
        $image5->setFilename('sculpture2.jpg')
            ->setAlt('Vue de détail de la sculpture')
            ->setTitle('Détail de la sculpture en bois')
            ->setPosition(1);
        $manager->persist($image5);
        $images[] = $image5;
        
        $image6 = new Image();
        $image6->setFilename('sculpture3.jpg')
            ->setAlt("Vue d'ensemble de la sculpture")
            ->setTitle("Vue d'ensemble de la sculpture")
            ->setPosition(2);
        $manager->persist($image6);
        $images[] = $image6;
        
        // Images pour les événements
        $image7 = new Image();
        $image7->setFilename('atelier1.jpg')
            ->setAlt("Photo de l'atelier de création de bijoux")
            ->setTitle("Atelier création de bijoux");
        $manager->persist($image7);
        $images[] = $image7;
        
        $image8 = new Image();
        $image8->setFilename('expo1.jpg')
            ->setAlt("Photo de l'exposition d'art contemporain")
            ->setTitle("Exposition d'art contemporain");
        $manager->persist($image8);
        $images[] = $image8;
        
        $image9 = new Image();
        $image9->setFilename('marche1.jpg')
            ->setAlt("Photo du marché des créateurs")
            ->setTitle("Marché des créateurs");
        $manager->persist($image9);
        $images[] = $image9;

        // Create a product with ManyToMany relation to images
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
            ->addCategory($categoryMaison)
            ->addImage($image1)
            ->addImage($image2);

        $manager->persist($product1);
        
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
            ->addCategory($categoryMode)
            ->addImage($image3);

        $manager->persist($product2);
        
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
            ->addCategory($categoryDeco)
            ->addImage($image4)
            ->addImage($image5)
            ->addImage($image6);

        $manager->persist($product3);
        
        // Création des événements
        
        // Événement 1: Atelier de création de bijoux
        $event1 = new Event();
        $event1->setTitle('Atelier de création de bijoux')
            ->setShortDescription('Apprenez à créer vos propres bijoux en perles naturelles')
            ->setLongDescription('Rejoignez-nous pour un atelier pratique où vous apprendrez à créer vos propres bijoux en perles naturelles. Tous les matériaux sont fournis et vous repartirez avec votre création. Cet atelier est adapté à tous les niveaux, aucune expérience préalable n\'est requise.')
            ->setLocation('Boutique Awen, 15 rue des Artisans, Paris')
            ->setStartDateTime(new \DateTimeImmutable('+7 days 14:00:00'))
            ->setEndDateTime(new \DateTimeImmutable('+7 days 17:00:00'))
            ->setStatus(EventStatus::Published) // Événement publié
            ->addImage($image7)
            ->addImage($image3);
            
        $manager->persist($event1);
        
        // Événement 2: Exposition d'art contemporain
        $event2 = new Event();
        $event2->setTitle('Exposition d\'art contemporain')
            ->setShortDescription('Découvrez les nouvelles œuvres de Jules Martin')
            ->setLongDescription('Une exposition exceptionnelle présentant les dernières créations de Jules Martin. Venez découvrir ses sculptures en bois et échanger avec l\'artiste sur son processus créatif. Un verre de bienvenue sera offert.')
            ->setLocation('Galerie Moderna, 8 avenue des Arts, Lyon')
            ->setStartDateTime(new \DateTimeImmutable('+14 days 18:00:00'))
            ->setEndDateTime(new \DateTimeImmutable('+21 days 20:00:00'))
            ->setStatus(EventStatus::Cancelled) // Événement annulé
            ->addImage($image8)
            ->addImage($image4)
            ->addImage($image5);
            
        $manager->persist($event2);
        
        // Événement 3: Marché des créateurs
        $event3 = new Event();
        $event3->setTitle('Marché des créateurs')
            ->setShortDescription('Rencontrez les créateurs locaux et découvrez leurs créations uniques')
            ->setLongDescription('Le marché des créateurs est l\'occasion idéale pour découvrir les talents locaux et leurs créations artisanales uniques. Bijoux, décorations, accessoires de mode, art... il y en a pour tous les goûts ! Venez nombreux soutenir l\'artisanat local.')
            ->setLocation('Place du marché, Nantes')
            ->setStartDateTime(new \DateTimeImmutable('+30 days 10:00:00'))
            ->setEndDateTime(new \DateTimeImmutable('+30 days 18:00:00'))
            ->setStatus(EventStatus::Published) // Événement publié
            ->addImage($image9);
            
        $manager->persist($event3);
        
        // Événement 4: Atelier en cours de préparation
        $event4 = new Event();
        $event4->setTitle('Atelier de décoration durable')
            ->setShortDescription('Créez des décorations écologiques pour votre intérieur')
            ->setLongDescription('Dans cet atelier, vous apprendrez à créer des décorations pour votre maison à partir de matériaux recyclés et durables. Une façon créative de donner une seconde vie à vos objets du quotidien tout en décorant votre intérieur avec style.')
            ->setLocation('MakerSpace, 25 rue de l\'Innovation, Bordeaux')
            ->setStartDateTime(new \DateTimeImmutable('+45 days 15:00:00'))
            ->setEndDateTime(new \DateTimeImmutable('+45 days 18:30:00'))
            ->setStatus(EventStatus::Draft) // Événement en brouillon
            ->addImage($image1);
            
        $manager->persist($event4);
        
        // Événement 5: Conférence terminée
        $event5 = new Event();
        $event5->setTitle('Conférence sur l\'artisanat local')
            ->setShortDescription('Échanges autour de l\'importance de l\'artisanat dans l\'économie locale')
            ->setLongDescription('Une conférence passionnante sur la place de l\'artisanat dans notre économie locale, avec des témoignages de créateurs et d\'experts du secteur. Un moment d\'échange et de partage autour de valeurs communes.')
            ->setLocation('Centre culturel, Toulouse')
            ->setStartDateTime(new \DateTimeImmutable('-15 days 10:00:00'))
            ->setEndDateTime(new \DateTimeImmutable('-15 days 12:30:00'))
            ->setStatus(EventStatus::Archived) // Événement archivé
            ->addImage($image8);
            
        $manager->persist($event5);
        
        // Gestion des relations Event-User via l'entité EventUser
        
        // Clara organise l'atelier de création de bijoux
        $event1->addUserWithStatus($creator, EventUserStatus::ORGANIZER);
        
        // Jules organise l'exposition d'art contemporain (annulée)
        $event2->addUserWithStatus($creator2, EventUserStatus::ORGANIZER);
        
        // Les deux créateurs organisent le marché des créateurs
        $event3->addUserWithStatus($creator, EventUserStatus::ORGANIZER);
        $event3->addUserWithStatus($creator2, EventUserStatus::ORGANIZER);
        
        // Clara organise l'atelier de décoration durable (en brouillon)
        $event4->addUserWithStatus($creator, EventUserStatus::ORGANIZER);
        
        // Jules organisait la conférence (archivée)
        $event5->addUserWithStatus($creator2, EventUserStatus::ORGANIZER);
        
        // L'utilisateur John est intéressé par l'atelier de création
        $event1->addUserWithStatus($user, EventUserStatus::INTERESTED);
        
        // John participe à l'exposition d'art (même si elle est annulée)
        $event2->addUserWithStatus($user, EventUserStatus::PARTICIPANT);
        
        // L'admin est invitée à tous les événements
        $event1->addUserWithStatus($admin, EventUserStatus::INVITED);
        $event2->addUserWithStatus($admin, EventUserStatus::INVITED);
        $event3->addUserWithStatus($admin, EventUserStatus::INVITED);
        $event4->addUserWithStatus($admin, EventUserStatus::INVITED);
        $event5->addUserWithStatus($admin, EventUserStatus::PARTICIPANT); // A participé à l'événement archivé
        
        // Jules est intéressé par l'atelier de Clara
        $event1->addUserWithStatus($creator2, EventUserStatus::INTERESTED);
        
        // Clara participe à l'exposition de Jules
        $event2->addUserWithStatus($creator, EventUserStatus::PARTICIPANT);
        
        // John a participé à la conférence archivée
        $event5->addUserWithStatus($user, EventUserStatus::PARTICIPANT);

        $manager->flush();
    }
}
