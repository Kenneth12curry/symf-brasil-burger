<?php

namespace App\Service\Cart;

use App\Repository\BoissonRepository;
use App\Repository\BurgerRepository;
use App\Repository\FriteRepository;
use App\Repository\MenuRepository;
use Container4CkAqYM\getBurgerControllerService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;
    protected $repoBurger;
    protected $repoFrite;
    protected $repoBoisson;
    protected $repoMenu;


    public function  __construct(SessionInterface $session,
    BurgerRepository $repoBurger,MenuRepository $repoMenu,
    FriteRepository $repoFrite,BoissonRepository $repoBoisson)
    {
        $this->session=$session;
        $this->repoBurger=$repoBurger;
        $this->repoFrite=$repoFrite;
        $this->repoMenu=$repoMenu;
        $this->repoBoisson=$repoBoisson;

    }


    public function add(int $id) {

        $panier = $this->session->get('panier',[]);

        if(!empty($panier[$id])){
            $panier[$id]++;

        }else{

            $panier[$id]= 1;
        }
        
        $this->session->set('panier',$panier);

    }

    public function remove(int $id) {

        $panier = $this->session->get('panier',[]);

        if(!empty($panier[$id])){

            unset($panier[$id]);

        }
        
        $this->session->set('panier',$panier);
    }


    public function getFullCart() : array {

        $panier= $this->session->get('panier',[]);

        $panierwithdata=[];

        foreach($panier as $id=>$quantity){

            if($burger=$this->repoBurger->find($id)!=null){

                $panierwithdata[] = [
                    'product'=>$this->repoBurger->find($id),
                    'quantity'=> $quantity
                ];

            }
            else if($frite=$this->repoFrite->find($id)!=null){
                $panierwithdata[] = [
                    'product'=>$this->repoFrite->find($id),
                    'quantity'=> $quantity
                ];
            }
            else if($boisson=$this->repoBoisson->find($id)!=null){
                $panierwithdata[] = [
                    'product'=>$this->repoBoisson->find($id),
                    'quantity'=> $quantity
                ];

            }else{

                $panierwithdata[] = [
                    'product'=>$this->repoMenu->find($id),
                    'quantity'=> $quantity
                ];

            }  

        }
        return $panierwithdata;
       
    }

    public function getTotal() : float 
    {

        $total = 0;
        $panierwithdata=$this->getFullCart();
        
        foreach($panierwithdata as $item){
            $totalItem = $item['product']->getPrix() * $item['quantity'];
            $total += $totalItem;
        }
        return $total;
    }
    

}

?>