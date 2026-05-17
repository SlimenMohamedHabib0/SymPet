<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ProduitRepository $produitRepository,
        CategorieRepository $categorieRepository
    ): Response {
        return $this->render('home/index.html.twig', [
            'produits'   => $produitRepository->findBy([], ['createdAt' => 'DESC'], 4),
            'categories' => $categorieRepository->findAll(),
        ]);
    }
}
