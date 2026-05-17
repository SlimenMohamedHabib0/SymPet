<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Repository\CommandeRepository;
use App\Service\PaginationService;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CommandeController extends AbstractController
{
    #[Route('/commande/valider', name: 'app_commande_valider')]
    #[IsGranted('ROLE_USER')]
    public function valider(Request $request, PanierService $panier, EntityManagerInterface $em): Response
    {
        $contenu = $panier->getContenu();
        if (empty($contenu)) {
            $this->addFlash('warning', 'Votre panier est vide');
            return $this->redirectToRoute('app_produit_index');
        }
        $commande = new Commande();
        $commande->setUser($this->getUser());
        $commande->setStatut('en_attente');
        $commande->setCreatedAt(new \DateTimeImmutable());
        $commande->setNumeroCommande('CMD-'.strtoupper(uniqid()));
        $total = 0;
        foreach ($contenu as $item) {
            $ligne = new LigneCommande();
            $ligne->setCommande($commande);
            $ligne->setProduit($item['produit']);
            $ligne->setQuantite($item['quantite']);
            $ligne->setPrixUnitaire($item['produit']->getPrix());
            $total += $item['produit']->getPrix() * $item['quantite'];
// decrementer stock
            $item['produit']->setStock($item['produit']->getStock() - $item['quantite']);
            $em->persist($ligne);
        }
        $commande->setTotal((string) $total);
        $em->persist($commande);
        $em->flush();
// stocker id commande en session pour Stripe
        $request->getSession()->set('commande_id', $commande->getId());
        return $this->redirectToRoute('app_paiement_payer', ['id' => $commande->getId()]);
    }
    #[Route('/commandes', name: 'app_commande_historique')]
    #[IsGranted('ROLE_USER')]
    public function historique(CommandeRepository $repo,
                               PaginationService $pagination, Request $request): Response
    {
        $query = $repo->findByUserQuery($this->getUser());
        $commandes = $pagination->paginate($query, $request, 5);
        return $this->render('commande/historique.html.twig', ['commandes' => $commandes]);
    }
    #[Route('/commandes/{id}', name: 'app_commande_detail')]
    public function detail(Commande $commande): Response
    {

        if ($commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        return $this->render('commande/detail.html.twig', ['commande' => $commande]);
    }

}
