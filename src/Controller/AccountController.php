<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account_settings')]
    public function settings(
        Request $request, 
        EntityManagerInterface $entityManager,
        \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
        
        // Mettre à jour la date de modification
        $user->setUpdatedAt(new \DateTimeImmutable());

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updates = [];

            // Vérifier si l'email a été modifié
            $newEmail = $form->get('email')->getData();
            if ($user->getEmail() !== $newEmail) {
                $user->setEmail($newEmail);
                $updates[] = 'email';
            }

            // Gestion du changement de mot de passe
            if ($plainPassword = $form->get('plainPassword')->getData()) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plainPassword
                );
                $user->setPassword($hashedPassword);
                $updates[] = 'mot de passe';
            }

            // Mise à jour des informations d'adresse
            $addressUpdated = false;
            $addressFields = ['address1', 'address2', 'zipCode', 'city'];
            foreach ($addressFields as $field) {
                $newValue = $form->get($field)->getData();
                $getter = 'get' . ucfirst($field);
                $setter = 'set' . ucfirst($field);
                
                if ($user->$getter() !== $newValue) {
                    $user->$setter($newValue);
                    $addressUpdated = true;
                }
            }
            if ($addressUpdated) {
                $updates[] = 'adresse';
            }

            // Gestion des messages de succès
            if (!empty($updates)) {
                $this->addFlash(
                    'success', 
                    'Vos informations ont été mises à jour avec succès (' . implode(', ', $updates) . ').'
                );
            } else {
                $this->addFlash('info', 'Aucune modification n\'a été effectuée.');
            }

            $entityManager->flush();
            return $this->redirectToRoute('account_settings');
        }

        return $this->render('account/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
