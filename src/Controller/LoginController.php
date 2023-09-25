<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\PasswordResetToken;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();

        if ($error) {
            $this->addFlash('error', 'Authentication failed. Please check your credentials.');
        } else {
            $this->addFlash('success', 'Welcome! You have successfully logged in.');
        }
        

        return $this->render('security/login.html.twig', [
            'last_email' => $lastEmail,
            'error' => $error,
        ]);  
    }

    #[Route (path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route('/password-reset-request', name: 'password_reset_request')]
    public function passwordResetRequest(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                $token = new PasswordResetToken();
                $token->setToken(bin2hex(random_bytes(32)));
                $token->setEspireAt(new \DateTime('+1 hour'));
                $token->setUser($user);

                $entityManager->persist($token);
                $entityManager->flush();

                // Envoyer un email de réinitialisation de mot de passe
                $email = (new Email())
                    ->from('noreply@yourdomain.com')
                    ->to($email)
                    ->subject('Réinitialisation de mot de passe')
                    ->html($this->renderView('email/password_reset.html.twig', ['token' => $token->getToken()]));

                $mailer->send($email);

                $this->addFlash('success', 'Un email de réinitialisation a été envoyé.');
            } else {
                $this->addFlash('error', 'Cette adresse email n\'existe pas.');
            }
        }

        return $this->render('security/password_reset_request.html.twig');
    }

    #[Route('/password-reset/{token}', name:'password_reset')]
    public function passwordReset(string $token, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    $tokenEntity = $entityManager->getRepository(PasswordResetToken::class)->findOneBy(['token' => $token]);

    if (!$tokenEntity || $tokenEntity->getEspireAt() < new \DateTime()) {
        $this->addFlash('error', 'Le token est invalide ou expiré.');
        return $this->redirectToRoute('password_reset_request');
    }

    $form = $this->createForm(YourPasswordResetFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $tokenEntity->getUser();
        $encodedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
        $user->setPassword($encodedPassword);

        $entityManager->remove($tokenEntity);
        $entityManager->flush();

        $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès.');

        // Rediriger vers la page de connexion
        return $this->redirectToRoute('login_page');
    }

    return $this->render('security/password_reset.html.twig', [
        'form' => $form->createView()
    ]);
}
}