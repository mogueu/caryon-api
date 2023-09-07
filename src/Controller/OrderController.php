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
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Item;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ItemRepository;

class OrderController extends AbstractController
{
    //get all orders
    #[Route('/api/orders', name: 'app_order', methods: ['get'])]
    public function index(OrderRepository $orderRepository, SerializerInterface $serializer): JsonResponse
    {
        $orders = $OrderRepository->findAll();
        $jsonOrders = $serializer->serialize($orders, 'json', ['groups' => 'getOrders']);
        return new JsonResponse($jsonOrders, Response::HTTP_OK, [], true);
    }

    //get one specific order
    #[Route('/api/orders/{id}', name: 'app_order_detail', methods: ['get'])]
    public function getOrder(Order $order, OrderRepository $orderRepository, SerializerInterface $serializer): JsonResponse
    {
        $jsonOrders = $serializer->serialize($orders, 'json', ['groups' => 'getOrders']);
        return new JsonResponse($jsonOrders, Response::HTTP_OK, [], true);
    }

    //add an order
    #[Route('/api/orders', name: 'app_order_add', methods: ['post'])]
    public function addOrder(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ItemRepository $itemRepository, ): JsonResponse
    {
        $order = $serializer->deserialize($request->getContent(), Order::class, 'json');

        $em->persist($order);
        $em->flush();

        $jsonOrder = $serializer->serialize($order, 'json', ['groups' => 'getOrders']);

        return new JsonResponse($jsonOrder, Response::HTTP_CREATED, [], true);
    }

    //edit an order
    #[Route('/api/orders/{id}', name: 'app_order_edit', methods: ['put'])]
    public function editOrder(Order $currentOrder, Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ItemRepository $itemRepository, ): JsonResponse
    {
        $order = $serializer->deserialize($request->getContent(), Order::class, 'json', [AbstractNormalize::OBJECT_TO_POPULATE => $currentOrder]);
        
        $em->persist($order);
        $em->flush();

        $jsonOrder = $serializer->serialize($order, 'json', ['groups' => 'getOrders']);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //delete an existing order from the database
    #[Route('/api/orders/{id}', name: 'app_order_delete', methods:['delete'])]
    public function deleteOrder(Order $order, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($order);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
