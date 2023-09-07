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
use App\Entity\Expense;
use App\Repository\ExpenseRepository;

class ExpenseController extends AbstractController
{
    //get all the expenses from database
    #[Route('/api/expenses', name: 'app_expense', methods:['get'])]
    public function index(ExpenseRepository $expenseRepository, SerializerInterface $serializer): JsonResponse
    {
        $expenses = $expenseRepository->findAll();
        $jsonExpenses = $serializer->serialize($expenses, 'json');
        return new JsonResponse($jsonExpenses, Response::HTTP_OK, [], true);
    }

    //get one specific expense from the database knowing its id
    //param converter is used to convert data
    #[Route('/api/expenses/{id}', name: 'app_expense_detail', methods:['get'])]
    public function getExpense(Expense $expense, ExpenseRepository $expenseRepository, SerializerInterface $serializer): JsonResponse
    {
        $jsonExpense = $serializer->serialize($expense, 'json');
        return new JsonResponse($jsonExpense, Response::HTTP_OK, [], true);
    }

    //create an expense with specific information from the request
    #[Route('/api/expenses', name: 'app_expense_add', methods:['post'])]
    public function addExpense(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $expense = $serializer->deserialize($request->getContent(), Expense::class, 'json');
        //set the created field
        $expense->setCreated(new \DateTime('now'));
        $em->persist($expense);
        $em->flush();

        $jsonExpense = $serializer->serialize($expense, 'json'/*, ['groups' => 'getProducts']*/);

        return new JsonResponse($jsonExpense, Response::HTTP_CREATED, [], true);
    }

    //edit an expense with specific information from the request
    #[Route('/api/expenses/{id}', name: 'app_expense_edit', methods:['put'])]
    public function editExpense(Expense $currentExpense, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $expense = $serializer->deserialize($request->getContent(), Expense::class, 'json', [AbstractNormalize::OBJECT_TO_POPULATE => $currentExpense]);
        $em->persist($expense);
        $em->flush();

        //$jsonExpense = $serializer->serialize($expense, 'json'/*, ['groups' => 'getProducts']*/);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //delete an existing expense from the database
    #[Route('/api/expenses/{id}', name: 'app_expense_delete', methods:['delete'])]
    public function deleteExpense(Expense $expense, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($expense);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
