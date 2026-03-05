<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Images;
use App\Form\ImageType;

class GalerieController extends AbstractController
{
    public function number(Request $request, EntityManagerInterface $em, $page): Response
    {
        $repository = $em->getRepository(Images::class);
        $images = $repository->findAll();

        $image = new Images();
        $image->setDate(new \DateTime('today'));
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        $resultats = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $auteur = $image->getAuteur();
            $repository = $em->getRepository(Images::class);
            $resultats = $repository->findBy(['auteur' => $auteur]);
        }

        return $this->render('galerie.html.twig', [
            'images' => $images,
            'pages' => $page,
            'form' => $form->createView(),
            'resultats' => $resultats,
        ]);
    }
}
?>