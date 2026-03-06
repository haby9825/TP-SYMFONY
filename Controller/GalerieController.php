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
        $offset = ($page - 1) * 4;
        $images = $repository->findBy([], ['id' => 'ASC'], 4, $offset);


        $image = new Images();
        $image->setDate(new \DateTime('today'));
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $auteur = $form->get('auteur')->getData();
            if (!empty($auteur)) {
                $images = $repository->findBy(['auteur' => $auteur]);
            }
        }

        return $this->render('galerie.html.twig', [
            'images' => $images,
            'pages' => $page,
            'form' => $form->createView(),
        ]);
    }
}
?>
