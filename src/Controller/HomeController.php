<?php

namespace App\Controller;

use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    #[Route('/home', name: 'app_home')]
    public function index(BurgerRepository $burgerRepository): Response
    {
        $datas=$burgerRepository->findAll();
        return $this->render('website/home/index.html.twig', [
            //Chargement de la liste des burgers au niveau de la vue
            "datas"=>$datas,
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/menu', name: 'app_show_menu')]
    public function showMenu(MenuRepository $menuRepository): Response
    {
        $datas=$menuRepository->findAll();
        return $this->render('website/home/menu.html.twig', [
            "datas"=>$datas,
            'controller_name' => 'HomeController',
        ]);
    }
}
