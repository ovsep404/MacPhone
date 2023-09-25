<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\Order1Type;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order/admin')]
class OrderAdminController extends AbstractController
{
    #[Route('/', name: 'app_order_admin_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('order_admin/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_order_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $order = new Order();
        $form = $this->createForm(Order1Type::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_order_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order_admin/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_admin_show', methods: ['GET'])]
    public function show(Order $order): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('order_admin/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(Order1Type::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order_admin/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    
    {   $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
