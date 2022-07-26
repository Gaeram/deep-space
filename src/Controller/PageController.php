<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use App\Service\CallApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function articlePage(ArticleRepository $articleRepository, $id, Request $request, EntityManagerInterface $entityManager){

        $article = $articleRepository->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $comment->setIsPublished(1);

            $comment->setAuthor($this->getUser());

            $comment->setArticle($article);

            $comment->setPublishedDate(new \DateTime('NOW'));

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute("article-page", [ 'id' => $article->getId()]);
        }

        return $this->render("article.html.twig", [
            "article" => $article,
            'form' => $form->createView()
        ]);
    }

    #[Route("/articles/search", name: "articles-search")]
    public function searchArticle(Request $request, ArticleRepository $articleRepository)
    {
        // Je récupère les valeurs de mon formulaire dans ma route
        $search = $request->query->get('search');

        // je vais créer une méthode dans mon Repository
        // Qui permet de retrouver du contenu enn fonction d'un mot
        // entré dans la barre de recherche
        $articles = $articleRepository->searchByWord($search);


        // Je renvoie un .twig en lui passant les articles trouvé
        // & les affiche
        return $this->render('search_articles.html.twig', [
            'articles' => $articles
        ]);
    }
}