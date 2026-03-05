<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Images;
use App\Form\ImageType;
use Symfony\Bundle\SecurityBundle\Attribute\Security;

class ImageController extends AbstractController
{
    #[Security("is_granted('ROLE_ADMIN')")]
    public function i(Request $request, EntityManagerInterface $em): Response
    {
        $image = new Images();
        $image->setDate(new \DateTime('today'));
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('fichier')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                //$safeFilename = $slugger->slug($originalFilename); (pour renommer le fichier, utilise slugger)
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                // le move_uploaded_files()
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ...gérer les pbs d'upload ici
                }
                // stocker le nom de l'image dans le champ que l'on vient de créer
                $image->setFichier($newFilename);
            }
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('galerie');
        }

        return $this->render('images.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}