<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitController extends AbstractController
{
    #[Route('/produits', name: 'app_produit_index')]
    public function index(
        Request $request,
        ProduitRepository $produitRepository,
        CategorieRepository $categorieRepository,
        PaginationService $paginationService
    ): Response {
        $categories  = $categorieRepository->findAll();
        $categorieId = $request->query->get('categorie') ? (int) $request->query->get('categorie') : null;
        $q           = $request->query->get('q', '');
        $prixMin     = $request->query->get('prix_min') ? (float) $request->query->get('prix_min') : null;
        $prixMax     = $request->query->get('prix_max') ? (float) $request->query->get('prix_max') : null;
        $tri         = $request->query->get('tri', 'recent');

        $query    = $produitRepository->searchQuery($q, $categorieId, $prixMin, $prixMax, $tri);
        $produits = $paginationService->paginate($query, $request, 8);

        return $this->render('produit/index.html.twig', [
            'produits'    => $produits,
            'categories'  => $categories,
            'q'           => $q,
            'categorieId' => $categorieId,
            'prixMin'     => $prixMin,
            'prixMax'     => $prixMax,
            'tri'         => $tri,
        ]);
    }

    #[Route('/produits/{id}', name: 'app_produit_detail')]
    public function detail(int $id, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
        ]);
    }
}
