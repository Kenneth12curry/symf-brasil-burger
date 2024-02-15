<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use App\Service\Cart\CartService;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;

class CommandeController extends AbstractController
{
    /*#[Route('/g/commande', name: 'app_g_commande')]
    public function index(CommandeRepository $repoCommande): Response
    {
        $datas=$repoCommande->findAll();
        return $this->render('commande/index.html.twig', [
            'datas'=>$datas,
            'controller_name' => 'CommandeController',
        ]);
    }*/

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    #[Route('/commande/create', name: 'app_commande_create')]
    public function create(Request $request,CommandeRepository $repoCommande,CartService $cartService): Response
    {

        //Récupérer le total des prix dans le panier;
        $total=$cartService->getTotal();
        //Si l'utilisateur clique sur le bouton enregistrer du formulaire
        if($request->request->has("btnSave")){

            $etat="En cours";
            //Validation

            if(trim($request->request->get("btnSave"))=='create'){
                //Générer automatiquement le numéro de la commande
                $numero="COMMANDE_N°".(count($repoCommande->findAll())+1);
                //Création de l'objet de la commande
                $commande=new Commande;
                $commande->setNumero($numero);
    
            }
           
            $clients=$this->getUser();
            //dd($clients);
            //users=$con->getSession($request,$usersRepository);
            //dd($burger);
            $date=new DateTime('now');
            $dmy=$date->format('d-m-y');
            //Donner des états aux attributs avec les setters
            $commande->setMontant($total);
            $commande->setEtat($etat);
            $commande->setDate($dmy);
            $commande->setClient($clients);
            //$commande->setClient();
            //Appel de la méthode save
            $repoCommande->save($commande,true);
        }

        return $this->render('commande/message.html.twig', []);
    }


    //Filtre
    #[Route('/g/commande', name: 'app_g_commande')]
    public function show(CommandeRepository $repoCommande,Request $request,
    ClientRepository $repoClient): Response
    {
        $tel="";
        $date="";
        if($request->request->has("tel") && $request->request->get("tel")!=""){
            $tel=$request->request->get("tel");
            //Récupération de ce client
            $client=$repoClient->findOneBy(["tel"=>$tel]);
            //Récupération les commandes de ce client
            $datas=$client->getCommandes();

        }
        else if($request->request->has("date") && $request->request->get("date")!=""){
            $date=$request->request->get("date"); // Récupérer la valeur du champ   
            //Récupération des commandes        
            $datas=$repoCommande->findBy(array("date"=>$date)); 
            //dd($datas);
        }
        else if($request->request->has("etat") && $request->request->get("etat")!=""){
            $etat=$request->request->get('etat');
            //Récupération des commandes
            $datas=$repoCommande->findBy(array("etat"=>$etat));
        }
        else{
            $datas=$repoCommande->findAll();//tous les commandes de la bd
        }

        return $this->render('commande/index.html.twig',
            [
                "datas"=>$datas,
                "tel"=>$tel,
                "date"=>$date,
                
            ]
        );
    }


     //Change l'état d'une commande
     #[Route('/g/commande/edit/{idCm}', name: 'app_g_edit_commande', methods:['GET'])]
     public function edit($idCm,Request $request,CommandeRepository $repoCommande): Response
     {
        $tel="";
        $date="";
        $commande=$repoCommande->findOneBy(['id'=>$idCm]);
        $commande->setEtat("Terminer");
        $commande1=$repoCommande->save($commande,true);
        $datas=$repoCommande->findAll();
         return $this->render('commande/index.html.twig',[
             "datas"=>$datas,
             "tel"=>$tel,
             "date"=>$date,
             "commande"=>$commande1,
             
         ]);
     }


    //annuler une commande
    #[Route('/g/commande/update/{idCm}', name: 'app_g_update_commande', methods:['GET'])]
    public function annuler($idCm,Request $request,CommandeRepository $repoCommande): Response
    {
        $tel="";
        $date="";
        $commande=$repoCommande->findOneBy(['id'=>$idCm]);
        $commande->setEtat("Annuler");
        $commande1=$repoCommande->save($commande,true);
        $datas=$repoCommande->findAll();
        return $this->render('commande/index.html.twig',[
            "datas"=>$datas,
            "tel"=>$tel,
            "date"=>$date,
            "com"=>$commande1,
        ]);
    }

    //Afficher les commandes d'un client
    #[Route('/g/commande/client', name: 'app_client_commande')]
    public function showlisteCommandeClient(Request $request,ClientRepository $repoClients): Response
    {
        // Récupérez la variable globale 'user' de l'environnement Twig
        $userGlobal = $this->twig->getGlobals()['user'];
        $datas=[];
        $datas=$userGlobal->getCommandes();

        return $this->render('commande/mescommandes.html.twig',[
            "datas"=>$datas, 
        ]);

    }

    //Afficher les details d'une commande
    /*#[Route('/g/commande/{idCm}', name: 'app_details_commande')]
    public function DetailsCommande($idCm,Request $request,CommandeRepository $repoCommande,CartService $cartService): Response
    {
        
        $commande=$repoCommande->findOneBy(['id'=>$idCm]);
        $commande1=$commande->getMontant();
        dd($commande1);
        if($commande->getMontant()==$total){
            $datas=$cartService->getFullCart();
        }

        return $this->render('commande/DetailsCommande.html.twig',[
            'total'=> $total,
            "items"=>$datas, 
            
        ]);
    }*/

    
}
