<?php

namespace App\Controller;

use App\Repository\BoissonRepository;
use App\Repository\BurgerRepository;
use App\Repository\FriteRepository;
use App\Repository\MenuRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    #[Route('/panier', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        $panierwithdata=$cartService->getFullCart();

        $total = $cartService->getTotal();
        
        //dd($panierwithdata);
        return $this->render('cart/index.html.twig', [
            'items'=> $panierwithdata,
            'total'=> $total,
        ]);

    }



   

    #[Route('/panier/add/{id}', name: 'cart_add')]
    public function add($id, CartService $cartService)
    {
        $cartService->add($id);
        //dd($session->get('panier'));
        return $this->redirectToRoute("cart_index");

    }


    #[Route('/panier/remove/{id}', name: 'cart_remove')]
    public function remove($id, CartService $cartService){

        $cartService->remove($id);
        return $this->redirectToRoute("cart_index");

    }

}
