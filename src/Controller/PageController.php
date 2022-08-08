<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Service\CallApiService;
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
    public function telescopPage( CallApiService $callApiService)

    {
        return $this->render("telescop.html.twig", [
            "data" => $callApiService->getSpacePicture()
        ]);
    }

    #[Route("/agence", name: "agence-page")]
    public function agencePage(){

        return $this->render("agence.html.twig");

    }

    #[Route("/project", name: "project-page")]
    public function projectPage(){

        return $this->render("project.html.twig");

    }

    #[Route("/articles", name: "articles-page")]
    public function articlesPage(ArticleRepository $articleRepository){

        $articles = $articleRepository->findAll();

        return $this->render("articles.html.twig",[
            "articles" => $articles
        ]);

    }

    #[Route("/article/{id}", name: "article-page")]
    public function articlePage(ArticleRepository $articleRepository, $id){

        $article = $articleRepository->find($id);

        return $this->render("article.html.twig", [
            "article" => $article
        ]);
    }


}