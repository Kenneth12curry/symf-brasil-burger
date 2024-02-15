<?php

// src/EventSubscriber/UserSubscriber.php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class UserSubscriber implements EventSubscriberInterface
{
    private $security;
    private $twig;

    public function __construct(Security $security, Environment $twig)
    {
        $this->security = $security;
        $this->twig = $twig;
    }

    public function onKernelController()
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = $this->security->getUser();

        // Passer l'utilisateur à Twig
        $this->twig->addGlobal('user', $user);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
    
}

?>