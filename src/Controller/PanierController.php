<?php

namespace App\Controller;

use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier_voir')]
    public function voir(PanierService $panier): Response
    {
        return $this->render('panier/index.html.twig', [
            'contenu' => $panier->getContenu(),
            'total' => $panier->getTotal(),
        ]);
    }
    #[Route('/panier/ajouter/{id}', name: 'app_panier_ajouter')]
    public function ajouter(int $id, PanierService $panier): Response
    {
        $ok = $panier->add($id);
        if ($ok) {
            $this->addFlash('success', 'Produit ajoute au panier!');
        } else {
            $this->addFlash('warning', 'Stock insuffisant!');
        }
        return $this->redirectToRoute('app_produit_index');
    }
    #[Route('/panier/supprimer/{id}', name: 'app_panier_supprimer')]
    public function supprimer(int $id, PanierService $panier): Response
    {
        $panier->remove($id);
        $this->addFlash('success', 'Produit supprime du panier');
        return $this->redirectToRoute('app_panier_voir');
    }
    #[Route('/panier/modifier/{id}/{qty}', name: 'app_panier_modifier')]
    public function modifier(int $id, int $qty, PanierService $panier): Response
    {
        $panier->updateQty($id, $qty);
        return $this->redirectToRoute('app_panier_voir');
    }
    #[Route('/panier/vider', name: 'app_panier_vider')]
    public function vider(PanierService $panier): Response
    {
        $panier->clear();
        $this->addFlash('success', 'Panier vidé avec succès');
        return $this->redirectToRoute('app_panier_voir');
    }

}
