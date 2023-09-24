<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/commandes', name: 'app_orders_')]
class OrdersController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function add(SessionInterface $session, ProductRepository $productsRepository, OrderRepository $ordersRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);
        

        if ($panier === []) {
            // $this->addFlash('message', 'Votre panier est vide');
            $this->addFlash('warning', 'Votre panier est vide!');
            return $this->redirectToRoute('cart_index');
        }


        $totalAmount = 0;

        //Le panier n'est pas vide, on crée la commande
        $order = new Order();

        // On remplit la commande
        $order->setUserID($this->getUser());
        $order->setOrderDate(new \DateTime());
        $order->setReference(uniqid());


        // On parcourt le panier pour créer les détails de commande
        foreach ($panier as $item => $quantity) {


            // On va chercher le produit
            $product = $productsRepository->find($item);

            // If the product is found
            if ($product !== null) {
                // Calculate the price for this item
                $price = $product->getPrice();

                // Calculate the total amount for this item and quantity
                $itemTotal = $price * $quantity;

                // Add the item total to the order's total amount
                $totalAmount += $itemTotal;

                $test = $ordersRepository->find($item);

                $price = $product->getPrice();

                if ($test !== null) {
                    $status = $test->getStatus();
                } else {

                    $status = 'default_status';
                }


                // On crée le détail de commande
                $order->setProductID($product);
                $order->setUnitPrice($price);
                $order->setQuantity($quantity);
                $order->setStatus($status);
                $order->setOrderDate(new \DateTime());


            }
        }

        // Set the total amount for the order
        $order->setTotal($totalAmount);
        
        // On persiste et on flush
        $em->persist($order);
        $em->flush();

        $session->remove('panier');
        
        
        $this->addFlash('success', 'Commande créée avec succès!');
        return $this->redirectToRoute('cart_index');
    }
}
