<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Entity\News;
use App\Entity\Category;
use App\Services\FixturesManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @var FixturesManager $fm
     */
    private $fm;

    /**
     * @param FixturesManager $fm
     */
    public function __construct(FixturesManager $fm){
        $this->fm = $fm;
    }

    /**
     * @Route("/category", name="category_all")
     */
    public function indexCategory(){
        $repository = $this->getDoctrine()
            ->getRepository(Category::class);

        $categories = $repository->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
    }

    /**
     * @param FixturesManager $fm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/category/add", name="category_add")
     */
    public function add(FixturesManager $fm){

        $repository = $this->getDoctrine()
            ->getRepository(Category::class);

        $category = $repository->findAll();

        $em = $this->getDoctrine()->getManager();
        for($i = 0; $i < 2; $i++){
            $category = new Category();
            $category->setName($fm->getFaker()->jobTitle);
            $category->setSlug($fm->getFaker()->word);
            $em->persist($category);
        }
        $em->flush();

        return $this->redirectToRoute('category_all');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/category/form", name="category_form")
     */
    public function AddForm( Request $request ) {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category_all');
        }

        return $this->render('category/form.html.twig',
            ['form'=>$form->createView()]
        );
    }

    /**
     * @param Category $category
     * @Route("/category/{id}", name="category_show")
     * @return Response
     */
    public function showCategory(Category $category){
        return $this->render(
            'category/detail.html.twig',
            ['category'=>$category]
        );
    }
}
