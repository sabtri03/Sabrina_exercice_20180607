<?php

namespace App\Controller;

use App\Form\NewsType;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\News;
use App\Services\FixturesManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsController extends AbstractController
{
    /**
     * @var FixturesManager $fm
     */
    private $fm;

    /**
     * NewsController constructor.
     * @param FixturesManager $fm
     */
    public function __construct( FixturesManager $fm ) {
        $this->fm = $fm;
    }


    /**
     * @Route("/", name="homeAction")
     */
    public function homeAction(){
        $repository = $this->getDoctrine()
            ->getRepository(News::class);

        $articles = $repository->findAll();

        return $this->render(
            'news/index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * @param FixturesManager $fm
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/news/add", name="news_add")
     */
    public function add( FixturesManager $fm ) {

        $repository = $this->getDoctrine()
            ->getRepository(News::class);
        $news = $repository->findAll();

        $em = $this->getDoctrine()->getManager();
        for($i = 0; $i < 2; $i++){
            $news = new News();
            $news->setTitle($fm->getFaker()->words(rand(3, 7), true));
            $news->setContent($fm->getFaker()->paragraph(3));
            $news->setImageUrl($fm->getFaker()->imageUrl(300,150));
            $news->setPublicationDate($fm->getFaker()->dateTime($max = 'now', $timezone = null) );
            $em->persist($news);
        }
        $em->flush();

        return $this->redirectToRoute('homeAction');
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/news/form", name="news_form")
     */
    public function AddForm( Request $request ) {

        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();
            return $this->redirectToRoute('homeAction');
        }

        return $this->render('news/form.html.twig',
            ['form'=>$form->createView()]
        );
    }

    /**
     * @Route("/news/{id}", name="news_show")
     */
    public function showArticle(News $news){

        return $this->render(
            'news/detail.html.twig',
            ['news'=>$news]
        );
    }






    /**
     * @Route("/news/update/{id}", name="news_edit")
     */
    public function update( News $news, Request $request ) {

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);
        $response = $this->handleForm($form, $news);

        if($response === null){
            return $this->render('news/form.html.twig',
                ['form'=>$form->createView()]
            );
        }
        return $response;
    }
    private function handleForm(FormInterface $form, News $news) {
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();
            $this->addFlash('info', 'produit sauvegardé');
            return $this->redirectToRoute('homeAction');
        }
        return null;
    }




    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/news/remove/{id}", name="news_remove")
     */
    public function delete( $id ) {
        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($news);

        $em->flush();
        return $this->redirectToRoute('homeAction');
    }

}
