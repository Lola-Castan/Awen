<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Enum\ProductStatus;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findPublishedProducts();
        
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
    
    #[Route('/product/{id}', name: 'app_product_show')]
    public function show(Product $product): Response
    {
        // Vérifie si le produit est publié, sinon renvoie une 404
        if ($product->getStatus() !== ProductStatus::Published) {
            throw $this->createNotFoundException('Ce produit n\'est pas disponible.');
        }
        
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/create', name: 'app_product_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setName($form->get('name')->getData());
            $product->setShortDescription($form->get('shortDescription')->getData());
            $product->setLongDescription($form->get('longDescription')->getData());
            $product->setStock($form->get('stock')->getData());
            $product->setWeight($form->get('weight')->getData());
            $product->setWidth($form->get('width')->getData());
            $product->setDepth($form->get('depth')->getData());
            $product->setHeight($form->get('height')->getData());
            $product->setPrice($form->get('price')->getData());
            $product->setShowcaseProduct($form->get('showcaseProduct')->getData());
            $product->setStatus(ProductStatus::Draft);
            $product->setCreatedAt(new \DateTimeImmutable());

            // todo : add more validation ?

            $entityManager->persist($product);
            $entityManager->flush();
        }

        return $this->render('product/create.html.twig', [
            'productForm' => $form,
        ]);
    }

}
