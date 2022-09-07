<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController


{
        #[Route("/article", name: "article")]
        public function Comment($id,EntityManagerInterface $entityManager,Request $request, CommentRepository $commentRepository, ArticleRepository $articleRepository) {

            $comment = new Comment();

            $form = $this->createForm(CommentFormType::class, $comment);

            $form->handleRequest($request);

            $articleRepository->find($id);

            $commentRepository->findAll();

            if ($form->isSubmitted()){
                $entityManager->persist($comment);
                $entityManager->flush();

                return $this->redirectToRoute("article-page");
            }

            return $this->render("article.html.twig",[
                "comment"=>$comment
            ]);

        }
}