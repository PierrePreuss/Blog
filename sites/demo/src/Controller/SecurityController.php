<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class SecurityController extends Controller
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function registration(){
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        return $this->render('security/registration.html.twig' , [
            'form' => $form->createView()
        ]);

    }
}
