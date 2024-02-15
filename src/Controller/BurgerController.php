<?php

namespace App\Controller;


use App\Entity\Burger;

use App\Repository\BurgerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BurgerController extends AbstractController
{
    #[Route('/g/burger', name: 'app_g_burger',methods:["GET"])]
    public function show(BurgerRepository $repoBurger): Response
    {
        //creation de la variable data et appelle de la méthode findAll() qui est 
        //définie dans la classe BurgerRepository grace à la variable de type AgenceRepository ($repoBurger)
        $datas=$repoBurger->findAll();
        return $this->render('burger/index.html.twig', [
            //Récupération les données et chargement dans la vue burger
            "datas"=>$datas,
            'controller_name' => 'BurgerController',
        ]);
    }

    #[Route('/g/burger/create', name: 'app_g_burger_create')]
    public function create(Request $request,BurgerRepository $repoBurger): Response
    {
        $errors=[];
        //Si l'utilisateur clique sur le bouton enrgistrer du formulaire
        if($request->request->has("btnSave")){

            $nom=$request->request->get("nomBurger");
            $prix=$request->request->get("prixBurger");
            $image=$request->request->get("imgBurger");
            $description=$request->request->get("descrBurger");
            //Validation
            if(empty($nom)){
                $errors['nom']="Le nom du burger est obligatoire";
            }
            if(empty($prix)){
                $errors['prix']="Le prix est obligatoire";
            }
            if(empty($image)){
                $errors['image']="L'image est obligatoire";
            }
            if(empty($description)){
                $errors['description']="La description est obligatoire";
            }
            if(count($errors)!=0){
                return $this->redirectToRoute('app_g_burger_create',[
                    "errors"=>$errors
                ]);
            }

            if(trim($request->request->get("btnSave"))=='create'){
                //Création de l'objet de type burger
                $burger=new Burger;
    
            }else{

                //récupérer l'id du burger  qui se trouve dans le champ caché
                $idBurger=$request->request->get('id');
                $burger=$repoBurger->find($idBurger);

            }
            
            //dd($burger);
            //Donner des états aux attributs avec les setters
            $burger->setNom($nom);
            $burger->setPrix($prix);
            $burger->setImage($image);
            $burger->setDescription($description);
            //Appel de la méthode save qui se trouve dans BurgerRepository
            $repoBurger->save($burger,true);
            //redirection vers la liste des burgers
            return $this->redirectToRoute('app_g_burger');
        }

        return $this->render('burger/nouveau.html.twig', [
            'controller_name' => 'BurgerController',
        ]);
    }

    //méthode pour modifier un burger
     #[Route('/g/burger/edit/{idBurger}', name: 'app_g_edit_burger',methods:["GET"])]
     public function edit($idBurger,BurgerRepository $repoBurger): Response
     {
        $burger =$repoBurger->findOneBy(['id'=>$idBurger]);
        $datas=$repoBurger->findAll();
        return $this->render('burger/nouveau.html.twig', [
                "datas"=>$datas,
                "burger"=>$burger
        ]);
     }

     //méthode pour supprimer un burger
    #[Route('/g/burger/destroy/{idBurger}', name: 'app_g_destroy_burger',methods:["GET"])]
    public function destroy($idBurger,BurgerRepository $repoBurger): Response
    {

        //récupération de la l'id de l'agence
        $burger=$repoBurger->find($idBurger);
        //Appel de la méthode remove qui se trouve dans AgenceRepository
        $repoBurger->remove($burger,true);
        //redirection vers la liste des agences
        return $this->redirectToRoute('app_g_burger');
    }



    //méthode pour afficher les détails d'un burger
    #[Route('/burger/details/{idBurger}', name: 'app_details_burger',methods:["GET"])]
    public function Details($idBurger,BurgerRepository $repoBurger): Response
    {
       $burger =$repoBurger->findOneBy(['id'=>$idBurger]);
       //dd($burger);
       //$datas=$repoBurger->findAll();
       return $this->render('burger/DetailsBurger.html.twig', [
               //"datas"=>$datas,
               "burger"=>$burger
       ]);
    }

 
}
