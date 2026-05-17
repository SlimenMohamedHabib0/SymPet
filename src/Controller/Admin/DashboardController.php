<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use App\Repository\AvisRepository;
#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(
        UserRepository $userRepository,
        CommandeRepository $commandeRepository,
        ProduitRepository $produitRepository,
        CategorieRepository $categorieRepository,
        AvisRepository $avisRepository
    ): Response {

        $nbClients = $userRepository->count([]);
        $nbCommandes = $commandeRepository->count([]);
        $caTotal = $commandeRepository->getCATotal();

        $nbProduits = $produitRepository->count([]);
        $nbCategories = $categorieRepository->count([]);
        $nbAvis = $avisRepository->count([]);

        $ruptureStock = $produitRepository->countProduitsRupture();

        $topProduit = $commandeRepository->getTopProduit();
        $topCategorie = $commandeRepository->getTopCategorie();

        $caParMois = $commandeRepository->getCAParMois();
        $commandesParMois = $commandeRepository->getCommandesParMois();

        $produitsParCategorie = $produitRepository->getProduitsParCategorie();
        $avisParNote = $avisRepository->getAvisParNote();
        $moyenneAvis = $avisRepository->getMoyenneAvis();

        return $this->render('admin/dashboard.html.twig', [

            'nbClients' => $nbClients,
            'nbCommandes' => $nbCommandes,
            'caTotal' => $caTotal,

            'nbProduits' => $nbProduits,
            'nbCategories' => $nbCategories,
            'nbAvis' => $nbAvis,
            'ruptureStock' => $ruptureStock,

            'topProduit' => $topProduit,
            'topCategorie' => $topCategorie,

            'caParMois' => $caParMois,
            'commandesParMois' => $commandesParMois,
            'produitsParCategorie' => $produitsParCategorie,

            'avisParNote' => $avisParNote,
            'moyenneAvis' => round($moyenneAvis, 1),

        ]);
    }
}
