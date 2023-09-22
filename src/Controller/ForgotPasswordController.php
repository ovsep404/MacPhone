<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{
    // Dans un contrôleur approprié, par exemple ForgotPasswordController.php
#[Route('/forgot-password', name: 'forgot_password_page')]
public function forgotPassword(): Response
{
    // Logique de réinitialisation du mot de passe ici
    return $this->render('forgot_password/index.html.twig');
}

}
