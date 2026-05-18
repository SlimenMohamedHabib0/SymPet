<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/produit')]
#[IsGranted('ROLE_ADMIN')]

final class ProduitController extends AbstractController
{
    #[Route(name: 'app_admin_produit_index', methods: ['GET'])]
    public function index(Request $request,
                          ProduitRepository $produitRepository,
                          CategorieRepository $categorieRepository,
                          PaginationService $paginationService): Response
    {
        $categories  = $categorieRepository->findAll();
        $categorieId = $request->query->get('categorie') ? (int) $request->query->get('categorie') : null;
        $q           = $request->query->get('q', '');
        $prixMin     = $request->query->get('prix_min') ? (float) $request->query->get('prix_min') : null;
        $prixMax     = $request->query->get('prix_max') ? (float) $request->query->get('prix_max') : null;
        $tri         = $request->query->get('tri', 'recent');

        $query    = $produitRepository->searchQuery($q, $categorieId, $prixMin, $prixMax, $tri);
        $produits = $paginationService->paginate($query, $request, 8);

        return $this->render('admin/produit/index.html.twig', [
            'produits'    => $produits,
            'categories'  => $categories,
            'q'           => $q,
            'categorieId' => $categorieId,
            'prixMin'     => $prixMin,
            'prixMax'     => $prixMax,
            'tri'         => $tri,
        ]);

    }

    #[Route('/new', name: 'app_admin_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit cree avec succes!');
            return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('admin/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Produit modifie avec succes!');

            return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit supprime avec succes!');
        }

        return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
