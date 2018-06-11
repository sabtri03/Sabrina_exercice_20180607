<?php

namespace App\Controller;

use App\Form\AuthorType;
use App\Entity\Author;
use App\Services\FixturesManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    /**
     * @var FixturesManager $fm
     */
    private $fm;

    /**
     * @param FixturesManager $fm
     */
    public function __construct( FixturesManager $fm ) {
        $this->fm = $fm;
    }

    /**
     * @Route("/author", name="authors_all")
     */
    public function indexAuthor()
    {
        $repository = $this->getDoctrine()
            ->getRepository(Author::class);

        $authors = $repository->findAll();

        return $this->render(
            'author/index.html.twig',
            ['authors' => $authors]
        );
    }

    /**
     * @param FixturesManager $fm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/author/add", name="author_add")
     */
    public function add( FixturesManager $fm ) {

        $repository = $this->getDoctrine()
            ->getRepository(Author::class);

        $author = $repository->findAll();

        $em = $this->getDoctrine()->getManager();
        for($i = 0; $i < 2; $i++){
            $author = new Author();
            $author->setName($fm->getFaker()->name);
            $author->setFirstname($fm->getFaker()->firstName);
            $author->setDateOfBirth($fm->getFaker()->dateTime($max = 'now', $timezone = null) );
            $author->setBiography($fm->getFaker()->text(200));
            $em->persist($author);
        }
        $em->flush();

        return $this->redirectToRoute('authors_all');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/author/form", name="author_form")
     */
    public function AddForm( Request $request ) {

        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('authors_all');
        }

        return $this->render('author/form.html.twig',
            ['form'=>$form->createView()]
        );
    }

    /**
     * @param Author $author
     * @Route("/author/{id}", name="author_show")
     * @return Response
     */
    public function showAuthor(Author $author){

        return $this->render(
            'author/detail.html.twig',
            ['author'=>$author]
        );
    }
}
