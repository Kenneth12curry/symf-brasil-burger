<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;




class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(UserRepository $userRepository): Response
    {
        $datas=$userRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'datas'=>$datas,
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/createAdmin', name: 'app_create_admin')]
    public function createAdmin(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        /*$admin = new Administrateur();
        $admin->setLogin('admin');
        $admin->setNom('bryant');// Remplacez par le nom d'utilisateur de votre choix
        $admin->setPrenom("michael");
        $plainPassword = 'password123'; // Remplacez par le mot de passe de votre choix
        $hashedPassword = $passwordHasher->hashPassword($admin, $plainPassword);
        $admin->setPassword($hashedPassword);
        
        // Si vous avez d'autres propriétés à définir pour l'admin, faites-le ici

        $entityManager->persist($admin);
        $entityManager->flush();*/

        // Création d'un User de type gestionnaire
        $gs = new User();
        $gs->setLogin('ges@gmail.com');
        $gs->setNom('vincent');// Remplacez par le nom d'utilisateur de votre choix
        $gs->setPrenom('olivier');
        $plainPassword = 'passer'; // Remplacez par le mot de passe de votre choix
        $hashedPassword = $passwordHasher->hashPassword($gs, $plainPassword);
        $gs->setPassword($hashedPassword);
        
        // Si vous avez d'autres propriétés à définir pour l'admin, faites-le ici

        $entityManager->persist($gs);
        $entityManager->flush();
        
    }

    //méthode pour supprimer un utilisateur
    #[Route('/user/destroy/{idUser}', name: 'app_destroy_user',methods:["GET"])]
    public function destroy($idUser,UserRepository $userRepository): Response
    {

        //récupération de l'utilisateur à partir de son id
        $user=$userRepository->find($idUser);
        //Appel de la méthode remove qui se trouve dans UserRepository
        //$userRepository->remove($user,true);
        //redirection vers la liste des utilisateurs
        return $this->redirectToRoute('app_admin');
    }


}
