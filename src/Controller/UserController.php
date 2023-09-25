<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/dashboard", name="user_dashboard")
     */
    #[Route('/user/dashboard', name: 'user_dashboard')]
    public function dashboard(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        $twoMonthsAgo = new \DateTime();
        $twoMonthsAgo->modify('-2 months');

        $orders = $orderRepository->findByUserAndDate($user, $twoMonthsAgo);

        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('user/index.html.twig', [
            'orders' => $orders
        ]);
    }
}
