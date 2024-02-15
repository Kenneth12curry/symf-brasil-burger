<?php

namespace App\Controller;

use App\Entity\Complement;
use App\Entity\Menu;
use App\Repository\MenuRepository;
use App\Repository\FriteRepository;
use App\Repository\OffreRepository;
use App\Repository\BurgerRepository;
use App\Repository\BoissonRepository;
use App\Repository\ComplementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    #[Route('/g/menu', name: 'app_g_menu')]
    public function show(MenuRepository $repoMenu): Response
    {
        $datas=$repoMenu->findAll();
        return $this->render('menu/index.html.twig', [
            "datas"=>$datas,
            'controller_name' => 'MenuController',
        ]);

    }



    #[Route('/g/menu/create', name: 'app_g_menu_create')]
    public function create(Request $request,MenuRepository $repoMenu,BurgerRepository $repoBurger,
    FriteRepository $repoFrite,BoissonRepository $repoBoisson,
    OffreRepository $repoOffre,ComplementRepository $repoComplement) : Response
    {
        $burgers=$repoBurger->findAll();
        $frites=$repoFrite->findAll();
        $boissons=$repoBoisson->findAll();
        $errors=[];
        //Si l'utilisateur clique sur le bouton enrgistrer du formulaire
        if($request->request->has("btnSave")){

            $nom=$request->request->get("nomMenu");
            $prix=$request->request->get("prixMenu");
            $image=$request->request->get("imgMenu");
            $description=$request->request->get("descrMenu");
            $burger=$request->request->get('MenuBurger');
            $frite=$request->request->get('MenuFrite');
            $boisson=$request->request->get('MenuBoisson');
            //Validation
            if(empty($nom)){
                $errors['nom']="Le nom du menu est obligatoire";
            }
            if(empty($prix)){
                $errors['prix']="Le prix est obligatoire";
            }
            if(empty($image)){
                $errors['image']="L'image est obligatoire";
            }
            if(empty($description)){
                $errors['description']="La description du menu est obligatoire";
            }

            if(count($errors)!=0){
                return $this->redirectToRoute('app_g_menu_create',[
                    "errors"=>$errors
                ]);
            }

            if(trim($request->request->get("btnSave"))=='create'){
                //Création de l'objet de type burger
                $menu=new Menu;
    
            }else{

                //récupérer l'id du burger  qui se trouve dans le champ caché
                $idMenu=$request->request->get('id');
                $menu=$repoMenu->find($idMenu);

            }
            $burger1=$repoOffre->findOneByNom($burger);
            $frite1=$repoComplement->findOneByNom($frite);
            $boisson1=$repoComplement->findOneByNom($boisson);

            //Donner des états aux attributs avec les setters
            $menu->setNom($nom);
            $menu->setPrix($prix);
            $menu->setImage($image);
            $menu->setDescription($description);
            $menu->setBurger($burger1);
            $menu->setFrite($frite1);
            $menu->setBoisson($boisson1);

            //Appel de la méthode save qui se trouve dans MenuRepository
            $repoMenu->save($menu,true);
            //redirection vers la liste des burgers
            return $this->redirectToRoute('app_g_menu');

        }

        return $this->render('menu/nouveau.html.twig', [
            "burgers"=>$burgers,
            "frites"=>$frites,
            "boissons"=>$boissons,
            'controller_name' => 'BurgerController',
        ]);
    }


    //méthode pour modifier un menu
    #[Route('/g/menu/edit/{idMenu}', name: 'app_g_edit_menu',methods:["GET"])]
    public function edit($idMenu,MenuRepository $repoMenu,BurgerRepository $repoBurger,
    FriteRepository $repoFrite,BoissonRepository $repoBoisson,): Response
    {
        $menu =$repoMenu->findOneBy(['id'=>$idMenu]);
        $datas=$repoMenu->findAll();
        $burgers=$repoBurger->findAll();
        $frites=$repoFrite->findAll();
        $boissons=$repoBoisson->findAll();
        return $this->render('menu/nouveau.html.twig', [
                "datas"=>$datas,
                "burgers"=>$burgers,
                "frites"=>$frites,
                "boissons"=>$boissons,
                "datas"=>$datas,
                "menu"=>$menu
            ]);
    }

    //méthode pour supprimer un menu
    #[Route('/g/menu/destroy/{idMenu}', name: 'app_g_destroy_menu',methods:["GET"])]
    public function destroy($idMenu,MenuRepository $repoMenu): Response
    {

        //récupération de la l'id du menu
        $menu=$repoMenu->find($idMenu);
        //Appel de la méthode remove qui se trouve dans MenuRepository
        $repoMenu->remove($menu,true);
        //redirection vers la liste des menus
        return $this->redirectToRoute('app_g_menu');

    }


     //méthode pour afficher les détails d'un menu
     #[Route('/menu/details/{idMenu}', name: 'app_details_menu',methods:["GET"])]
     public function Details($idMenu,MenuRepository $repoMenu): Response
     {
        $menu=$repoMenu->findOneBy(['id'=>$idMenu]);
        //dd($burger);
        //$datas=$repoBurger->findAll();
        return $this->render('menu/DetailsMenu.html.twig', [
                //"datas"=>$datas,
                "menu"=>$menu
        ]);
     }
 
  

}
