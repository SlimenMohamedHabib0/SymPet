<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Produit;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AvisController extends AbstractController
{
    #[Route('/avis/ajouter/{id}', name: 'app_avis_ajouter')]
    #[IsGranted('ROLE_USER')]
    public function ajouter(
        Produit $produit,
        Request $request,
        EntityManagerInterface $em,
        AvisRepository $avisRepo
    ): Response {
        // check already reviewed
        $existant = $avisRepo->findOneBy([
            'user'    => $this->getUser(),
            'produit' => $produit,
        ]);
        if ($existant) {
            $this->addFlash('warning', 'Vous avez déjà laissé un avis pour ce produit');
            return $this->redirectToRoute('app_produit_detail', ['id' => $produit->getId()]);
        }

        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setUser($this->getUser());
            $avis->setProduit($produit);
            $avis->setCreatedAt(new \DateTimeImmutable());
            $em->persist($avis);
            $em->flush();
            $this->addFlash('success', 'Votre avis a été enregistré!');
            return $this->redirectToRoute('app_produit_detail', ['id' => $produit->getId()]);
        }

        return $this->render('avis/ajouter.html.twig', [
            'form'    => $form,
            'produit' => $produit,
        ]);
    }
}
