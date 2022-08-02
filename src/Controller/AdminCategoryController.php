<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    #[Route("/admin/create-category", name: "admin-create-category")]
    public function createCategory(EntityManagerInterface $entityManager, Request $request){

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('admin/form-category.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route("/admin/categories", name: "admin-categories")]
    public function showCategories(CategoryRepository $categoryRepository){

        $categories = $categoryRepository->findAll();

        return $this->render("admin/categories.html.twig", [
            'categories'=>$categories
        ]);
    }

    #[Route("/admin/category/{id}", name: "admin-category")]
    public function showCategory($id, CategoryRepository $categoryRepository){

        $category = $categoryRepository->find($id);

        return $this->render("admin/category.html.twig", [
            'category'=>$category
        ]);

    }

    #[Route("/admin/category/update/{id}", name: "admin-category-update")]
    public function updateCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, Request $request){

        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('admin/form-category.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route("/admin/category/delete/{id}", name: "admin-category-delete" )]
    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager){

        $category = $categoryRepository->find($id);

        if(!is_null($category)){

            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash("success", "Votre catégorie à bien été supprimée");
        } else {
            $this->addFlash("error", "La catégorie est introuvable !");
        }
        return $this->redirectToRoute("admin-categories");
    }
}