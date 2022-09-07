<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;

class CommentController extends AbstractController
{
        public function Comment(Request $request, Conference $conference, CommentRepository $commentRepository): Response {

            $comment = new Comment();

            $form = $this->createForm(CommentFormType::class, $comment);

        }
}