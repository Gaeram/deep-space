<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route ("login/signup", name: "sign-up")]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $user = new User();

        $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);


        if ($form->isSubmitted()&&$form->isValid()){

            $plainPassword=$form->get('password')->getData();

            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);

            $user->setPassword($hashedPassword);

            $entityManager->persist($user);

            $entityManager->flush();

            $this->addFlash('success','Compte créé');

            return $this->redirectToRoute("home");
        }

        return $this->render('login/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}