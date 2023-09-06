<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Supplier;
use App\Entity\Product;
use App\Repository\SupplierRepository;
use App\Repository\ProductRepository;

class SupplierController extends AbstractController
{
    //get all the suppliers from database
    #[Route('/api/suppliers', name: 'app_supplier', methods:['get'])]
    public function index(SupplierRepository $supplierRepository, SerializerInterface $serializer): JsonResponse
    {
        $suppliers = $supplierRepository->findAll();
        $jsonSuppliers = $serializer->serialize($suppliers, 'json');
        return new JsonResponse($jsonSuppliers, Response::HTTP_OK, [], true);
    }

    //get one specific supplier from the database knowing its id
    //param converter is used to convert data
    #[Route('/api/suppliers/{id}', name: 'app_supplier_detail', methods:['get'])]
    public function getBrand(Supplier $supplier, SupplierRepository $SupplierRepository, SerializerInterface $serializer): JsonResponse
    {
        $jsonSupplier = $serializer->serialize($supplier, 'json');
        return new JsonResponse($jsonSupplier, Response::HTTP_OK, [], true);
    }

    //create a new supplier with specific information front the request
    #[Route('/api/suppliers', name: 'app_supplier_add', methods:['post'])]
    public function addSupplier(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $supplier = $serializer->deserialize($request->getContent(), Supplier::class, 'json');
        $em->persist($supplier);
        $em->flush();

        $jsonSupplier = $serializer->serialize($supplier, 'json'/*, ['groups' => 'getProducts']*/);

        return new JsonResponse($jsonSupplier, Response::HTTP_CREATED, [], true);
    }

    //delete an existing supplier from the database
    #[Route('/api/suppliers/{id}', name: 'app_supplier_delete', methods:['delete'])]
    public function deleteSupplier(Supplier $supplier, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($supplier);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
