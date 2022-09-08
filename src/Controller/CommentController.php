<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
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
        #[Route("/comment", name: "comment")]
        public function Comment($id,UserRepository $userRepository,EntityManagerInterface $entityManager,Request $request, CommentRepository $commentRepository, ArticleRepository $articleRepository) {

            $article = $articleRepository->find($id);

            $user = $userRepository->findBy($id);

            $comment = new Comment();

            $comment->setIsPublished(1);

            $comment->setAuthor($user);

            $comment->setUser($this->getUser());

            $comment->setArticle($article);

            $comment->setPublishedDate(new \DateTime('NOW'));

            $form = $this->createForm(CommentFormType::class, $comment);

            $form->handleRequest($request);


            if ($form->isSubmitted()){
                $entityManager->persist($comment);
                $entityManager->flush();

                return $this->redirectToRoute("article-page");
            }

            return $this->render("article.html.twig",[
                "comment"=>$comment,
                "form"=>$form
            ]);

        }
}