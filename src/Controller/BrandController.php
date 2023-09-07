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
use App\Entity\Brand;
use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;

class BrandController extends AbstractController
{
    //get all the brands from database
    #[Route('/api/brands', name: 'app_brand', methods:['get'])]
    public function index(BrandRepository $brandRepository, SerializerInterface $serializer): JsonResponse
    {
        $brands = $brandRepository->findAll();
        $jsonBrands = $serializer->serialize($brands, 'json', ['groups' => 'getBrands']);
        return new JsonResponse($jsonBrands, Response::HTTP_OK, [], true);
    }

    //get one specific brand from the database knowing its id
    //param converter is used to convert data
    #[Route('/api/brands/{id}', name: 'app_brand_detail', methods:['get'])]
    public function getBrand(Brand $brand, BrandRepository $brandRepository, SerializerInterface $serializer): JsonResponse
    {
        $jsonBrand = $serializer->serialize($brand, 'json', ['groups' => 'getBrands']);
        return new JsonResponse($jsonBrand, Response::HTTP_OK, [], true);
    }

    //create a new brand with specific information front the request
    #[Route('/api/brands', name: 'app_brand_add', methods:['post'])]
    public function addBrand(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $brand = $serializer->deserialize($request->getContent(), brand::class, 'json');
        //set the created field
        $brand->setCreated(new \DateTime('now'));

        $em->persist($brand);
        $em->flush();

        $jsonBrand = $serializer->serialize($brand, 'json', ['groups' => 'getBrands']);

        return new JsonResponse($jsonBrand, Response::HTTP_CREATED, [], true);
    }

    //update a brand with specific information front the request
    #[Route('/api/brands/{id}', name: 'app_brand_edit', methods:['put'])]
    public function editBrand(Brand $currentBrand, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $brand = $serializer->deserialize($request->getContent(), brand::class, 'json', [AbstractNormalize::OBJECT_TO_POPULATE => $currentBrand]);

        $em->persist($brand);
        $em->flush();

        //$jsonBrand = $serializer->serialize($brand, 'json', ['groups' => 'getBrands']);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //delete an existing brand from the database
    #[Route('/api/brands/{id}', name: 'app_brand_delete', methods:['delete'])]
    public function deleteBrand(Brand $brand, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($brand);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
