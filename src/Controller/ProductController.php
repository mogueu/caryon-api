<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Supplier;
use App\Repository\ProductRepository;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\SupplierRepository;

class ProductController extends AbstractController
{
    //get all the products from database
    #[Route('/api/products', name: 'app_product', methods:['get'])]
    public function index(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findAll();
        $jsonProducts = $serializer->serialize($products, 'json', ['groups' => 'getProducts']);
        return new JsonResponse($jsonProducts, Response::HTTP_OK, [], true);
    }

    //get one specific product from the database knowing its id
    //param converter is used to convert data
    #[Route('/api/products/{id}', name: 'app_product_detail', methods:['get'])]
    public function getProduct(Product $product, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $jsonProduct = $serializer->serialize($product, 'json', ['groups' => 'getProducts']);
        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }

    //create a new product with specific information front the request
    #[Route('/api/products', name: 'app_product_add', methods:['post'])]
    public function addProduct(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, BrandRepository $brandRepository, CategoryRepository $categoryRepository): JsonResponse
    {
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json');
        
        // get data as table
        $content = $request->toArray();

        // get ids
        $idCategory = $content['categoryId'] ?? -1;
        $idBrand = $content['brandId'] ?? -1;

        // set brand and category
        $product->setBrand($brandRepository->find($idBrand));
        $product->setCategory($categoryRepository->find($idCategory));

        //set quantity to 0 for each new product
        $product->setQuantity(0);
        $em->persist($product);
        $em->flush();

        $jsonProduct = $serializer->serialize($product, 'json', ['groups' => 'getProducts']);

        return new JsonResponse($jsonProduct, Response::HTTP_CREATED, [], true);
    }

    // update a product with specific information front the request
    #[Route('/api/products/{id}', name: 'app_product_edit', methods:['put'])]
    public function editProduct(Product $currentProduct, Request $request, EntityManagerInterface $em, SerializerInterface $serializer, BrandRepository $brandRepository, CategoryRepository $categoryRepository): JsonResponse
    {
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json', [AbstractNormalize::OBJECT_TO_POPULATE => $currentProduct]);

        
        // get data as table
        $content = $request->toArray();

        // get ids
        $idCategory = $content['categoryId'] ?? -1;
        $idBrand = $content['brandId'] ?? -1;

        // set brand and category
        $product->setBrand($brandRepository->find($idBrand));
        $product->setCategory($categoryRepository->find($idCategory));

        //set quantity to 0 for each new product
        $product->setQuantity(0);
        $em->persist($product);
        $em->flush();

        //$jsonProduct = $serializer->serialize($product, 'json', ['groups' => 'getProducts']);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //delete an existing product from the database
    #[Route('/api/products/{id}', name: 'app_product_delete', methods:['delete'])]
    public function deleteProduct(Product $product, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($product);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
