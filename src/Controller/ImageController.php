<?php

namespace App\Controller;

use App\Entity\Image;
use App\Services\FixturesManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
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
     * @Route("/image", name="image_all")
     */
    public function indexImage(){
        $repository = $this->getDoctrine()
            ->getRepository(Image::class);

        $images = $repository->findAll();

        return $this->render(
            'image/index.html.twig',
            ['images' => $images]
        );
    }

    /**
     * @param FixturesManager $fm
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/image/add", name="image_add")
     */
    public function add( FixturesManager $fm ) {

        $repository = $this->getDoctrine()
            ->getRepository(Image::class);
        $image = $repository->findAll();

        $em = $this->getDoctrine()->getManager();
        for($i = 0; $i < 2; $i++){
            $image = new Image();
            $image->setTitle($fm->getFaker()->jobTitle);
            $image->setUrl($fm->getFaker()->imageUrl(300,150));
            $em->persist($image);
        }
        $em->flush();

        return $this->redirectToRoute('image_all');
    }

    /**
     * @Route("/image/{id}", name="image_show")
     */
    public function showImage(Image $image){

        return $this->render(
            'image/detail.html.twig',
            ['image'=>$image]
        );
    }
}
