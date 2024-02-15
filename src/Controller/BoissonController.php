<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Repository\BoissonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoissonController extends AbstractController
{
    #[Route('/g/boisson', name: 'app_g_boisson')]
    public function index(BoissonRepository $repoBoisson): Response
    {
        $datas=$repoBoisson->findAll();
        return $this->render('boisson/index.html.twig', [
            'datas'=>$datas,
            'controller_name' => 'BoissonController',
        ]);
    }

    #[Route('/g/boisson/create', name: 'app_g_boisson_create')]
    public function create(Request $request,BoissonRepository $repoBoisson): Response
    {
        $datas=$repoBoisson->findAll();
        $errors=[];
        //Si l'utilisateur clique sur le bouton enrgistrer du formulaire
        if($request->request->has("btnSave")){

            $nom=$request->request->get("nomBoisson");
            $prix=$request->request->get("prixBoisson");
            $image=$request->request->get("imgBoisson");
            //Validation
            if(empty($nom)){
                $errors['nom']="nom obligatoire";
            }
            if(empty($prix)){
                $errors['prix']="Prix est obligatoire";
            }
            if(empty($image)){
                $errors['image']="L'image est obligatoire";
            }
            if(count($errors)!=0){
                return $this->redirectToRoute('app_g_boisson_create',[
                    "errors"=>$errors
                ]);
            }

            if(trim($request->request->get("btnSave"))=='create'){
                //Création de l'objet de type frite
                $boisson=new Boisson;
    
            }else{

                //récupérer l'id du frite  qui se trouve dans le champ caché
                $idboisson=$request->request->get('id');
                $boisson=$repoBoisson->find($idboisson);

            }
            //Donner des états aux attributs avec les setters
            $boisson->setNom($nom);
            $boisson->setPrix($prix);
            $boisson->setImage($image);
           
          
            //Appel de la méthode save qui se trouve dans BurgerRepository
            $repoBoisson->save($boisson,true);
            //redirection vers la liste des burgers
            return $this->redirectToRoute('app_g_boisson');
        }

        return $this->render('boisson/index.html.twig', [
            'datas'=>$datas,
            'controller_name' => 'BoissonController',
        ]);
    }

    //méthode pour modifier un burger
    #[Route('/g/boisson/edit/{idboisson}', name: 'app_g_edit_boisson',methods:["GET"])]
    public function edit($idboisson,BoissonRepository $repoBoisson): Response
    {
        $boisson =$repoBoisson->findOneBy(['id'=>$idboisson]);
        $datas=$repoBoisson->findAll();
        return $this->render('boisson/index.html.twig', [
                "datas"=>$datas,
                "boisson"=>$boisson
        ]);
    }

    //méthode pour supprimer une frite
    #[Route('/g/boisson/destroy/{idboisson}', name: 'app_g_destroy_boisson',methods:["GET"])]
    public function destroy($idboisson,BoissonRepository $repoBoisson): Response
    {

        //récupération de la l'id de l'agence
        $boisson=$repoBoisson->find($idboisson);
        //Appel de la méthode remove qui se trouve dans AgenceRepository
        $repoBoisson->remove($boisson,true);
        //redirection vers la liste des agences
        return $this->redirectToRoute('app_g_boisson');
    }

}
