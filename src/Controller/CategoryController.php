<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class CategoryController extends AbstractController
{
   //get all the categories from database
   #[Route('/api/categories', name: 'app_category', methods:['get'])]
   public function index(CategoryRepository $categoryRepository, SerializerInterface $serializer): JsonResponse
   {
       $categories = $categoryRepository->findAll();
       $jsoncategoriess = $serializer->serialize($categories, 'json', ['groups' => 'getCategories']);
       return new JsonResponse($jsoncategoriess, Response::HTTP_OK, [], true);
   }

   //get one specific category from the database knowing its id
    //param converter is used to convert data
    #[Route('/api/categories/{id}', name: 'app_category_detail', methods:['get'])]
    public function getCategory(Category $category, CategoryRepository $categoryRepository, SerializerInterface $serializer): JsonResponse
    {
        $jsonCategory = $serializer->serialize($category, 'json', ['groups' => 'getCategories']);
        return new JsonResponse($jsonCategory, Response::HTTP_OK, [], true);
    }

    //create a new category with specific information front the request
    #[Route('/api/categories', name: 'app_category_add', methods:['post'])]
    public function addCategory(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $category = $serializer->deserialize($request->getContent(), Category::class, 'json');
        //set the created field
        $category->setCreated(new \DateTime('now'));

        $em->persist($category);
        $em->flush();

        $jsonCategory = $serializer->serialize($category, 'json', ['groups' => 'getCategories']);

        return new JsonResponse($jsonCategory, Response::HTTP_CREATED, [], true);
    }

    //delete an existing category from the database
    #[Route('/api/categories/{id}', name: 'app_category_delete', methods:['delete'])]
    public function deleteCategory(Category $category, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($category);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
