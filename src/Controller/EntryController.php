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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Entry;
use App\Entity\Product;
use App\Entity\Supplier;
use App\Repository\EntryRepository;
use App\Repository\ProductRepository;
use App\Repository\SupplierRepository;

class EntryController extends AbstractController
{
    //get all entries
    #[Route('/api/entries', name: 'app_entry', methods: ['get'])]
    public function index(EntryRepository $entryRepository, SerializerInterface $serializer): JsonResponse
    {
        $entries = $entryRepository->findAll();
        $jsonEntries = $serializer->serialize($entries, 'json', ['groups' => 'getEntries']);
        return new JsonResponse($jsonEntries, Response::HTTP_OK, [], true);
    }

    //get one specific entry
    //param converter is used to convert data
    #[Route('/api/entries/{id}', name: 'app_entry_detail', methods: ['get'])]
    public function getEntry(Entry $entry, EntryRepository $entryRepository, SerializerInterface $serializer): JsonResponse
    {
        $jsonEntry = $serializer->serialize($entry, 'json', ['groups' => 'getEntries']);
        return new JsonResponse($jsonEntry, Response::HTTP_OK, [], true);
    }

    // add new entry to the database
    #[Route('/api/entries', name: 'app_entry_add', methods: ['post'])]
    public function addEntry(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, SupplierRepository $supplierRepository, ProductRepository $productRepository,ValidatorInterface $validator ): JsonResponse
    {
        $entry = $serializer->deserialize($request->getContent(), Entry::class, 'json');

        // test errors
        $errors = $validator->validate($entry);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        // get data as table
        $content = $request->toArray();

        // set product and supplier's id
        $idProduct = $content['productId'] ?? -1;
        $idSupplier = $content['supplierId'] ?? -1;

        // set supplier and product
        $entry->setProduct($productRepository->find($idProduct));
        $entry->setSupplier($supplierRepository->find($idSupplier));

        $em->persist($entry);
        $em->flush();

        $jsonEntry = $serializer->serialize($entry, 'json', ['groups' => 'getEntries']);

        return new JsonResponse($jsonEntry, Response::HTTP_CREATED, [], true);
    }

    // update an entry
    //param converter is used to convert data
    #[Route('/api/entries/{id}', name: 'app_entry_edit', methods: ['put'])]
    public function editEntry(Entry $currentEntry, Request $request, EntityManagerInterface $em, SerializerInterface $serializer, SupplierRepository $supplierRepository, ProductRepository $productRepository, ValidatorInterface $validator): JsonResponse
    {
        $entry = $serializer->deserialize($request->getContent(), Entry::class, 'json', [AbstractNormalize::OBJECT_TO_POPULATE => $currentEntry]);

        // test errors
        $errors = $validator->validate($entry);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        // get data as table
        $content = $request->toArray();

        // get ids
        $idProduct = $content['productId'] ?? -1;
        $idSupplier = $content['supplierId'] ?? -1;

        // set brand and category
        $entry->setProduct($productRepository->find($idProduct));
        $entry->setSupplier($supplierRepository->find($idSupplier));

        $em->persist($entry);
        $em->flush();

        //$jsonEntry = $serializer->serialize($entry, 'json', ['groups' => 'getEntries']);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //delete an entry
    //param converter is used to convert data
    #[Route('/api/entries/{id}', name: 'app_entry_delete', methods: ['delete'])]
    public function deleteEntry(Entry $entry, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $em->remove($entry);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
