<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Config\Security\PasswordHasherConfig;
use App\Form\UserType;

class UserController extends AbstractController
{
    #[Route ("/signup", name: "sign-up")]
    public function index(UserPasswordHasherInterface $passwordHasher)
    {
        // ... e.g. get the user data from a registration form
        $user = new User();
        $plaintextPassword =  "mot de passe";

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        // ...
    }
}