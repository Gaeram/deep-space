<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminArticleController extends AbstractController
{
    #[Route("/admin/create-article", name: "admin-create-article")]
    public function createArticle(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger){

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $image = $form->get('image')->getData();

            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = $slugger->slug($originalFilename);

            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            $image->move(
                $this->getParameter('images_directory'),
                $newFilename
            );

            $article->setImage($newFilename);

            $entityManager->persist($article);
        }

        return $this->render("admin/form-article.html.twig", [
            'form' => $form->createView()
        ]);


    }

    #[Route("/admin/articles", name: "admin-articles")]
    public function showArticles(ArticleRepository $articleRepository){

        $article = $articleRepository->findAll();

        return $this->render('admin/articles.html.twig', [
            "article"=>$article
        ]);
    }

    #[Route("/admin/article/{id}", name: "admin-article")]
    public function showArticle(ArticleRepository $articleRepository, $id){

        $article = $articleRepository->find($id);

        return $this->render('admin/article.html.twig' ,[
            "article"=>$article
        ]);
    }

    #[Route("/admin/article/update/{id}", name: "admin-article-update")]
    public function updateArticle(EntityManagerInterface $entityManager,Request $request,ArticleRepository $articleRepository,SluggerInterface $slugger, $id){

        $article = $articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $image = $form->get('image')->getData();

            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = $slugger->slug($originalFilename);

            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            $image->move(
                $this->getParameter('images_directory'),
                $newFilename
            );

            $article->setImage($newFilename);

            $entityManager->persist($article);
        }

        return $this->render("admin/form-article.html.twig", [
            'form' => $form->createView(),
            'article' => $article
        ]);

    }

    #[Route("/admin/article/delete/{id}", name: "admin-article-delete")]
    public function deleteArticle(ArticleRepository $articleRepository, EntityManagerInterface $entityManager, $id){

        $article = $articleRepository->find($id);

        if(!is_null($article)){

            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', 'Votre article à bien été supprimé');
        } else {
            $this->addFlash('error', 'Article Introuvable !');
        }

        return $this->redirectToRoute('admin-articles');
    }
}