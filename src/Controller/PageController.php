<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route("/", name:"home")]
    public function homePage(ArticleRepository $articleRepository){

        $lastArticles = $articleRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render("index.html.twig", [
            'lastArticles' => $lastArticles
        ]);

    }

    #[Route("/gjr-telescop", name: "telescop-page")]
    public function  telescopPage(){

        return $this->render("telescop.html.twig");

    }

    #[Route("/agence", name: "agence-page")]
    public function agencePage(){

        return $this->render("agence.html.twig");

    }

    #[Route("/project", name: "project-page")]
    public function projectPage(){

        return $this->render("project.html.twig");

    }


}