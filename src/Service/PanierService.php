<?php
namespace App\Service;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;
class PanierService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProduitRepository $produitRepository
    ) {}
    private function getSession() {
        return $this->requestStack->getSession();
    }
    public function add(int $produitId, int $qty = 1): bool
    {
        $produit = $this->produitRepository->find($produitId);
        if (!$produit) return false;
        $panier = $this->getSession()->get('panier', []);
        $qteActuelle = $panier[$produitId] ?? 0;
        if (($qteActuelle + $qty) > $produit->getStock()) return false;
        $panier[$produitId] = $qteActuelle + $qty;
        $this->getSession()->set('panier', $panier);
        return true;
    }
    public function remove(int $produitId): void {
        $panier = $this->getSession()->get('panier', []);
        unset($panier[$produitId]);
        $this->getSession()->set('panier', $panier);
    }
    public function updateQty(int $produitId, int $qty): void {
        if ($qty <= 0) { $this->remove($produitId); return; }
        $produit = $this->produitRepository->find($produitId);
        if ($produit && $qty <= $produit->getStock()) {
            $panier = $this->getSession()->get('panier', []);
            $panier[$produitId] = $qty;
            $this->getSession()->set('panier', $panier);
        }
    }
    public function getContenu(): array {
        $panier = $this->getSession()->get('panier', []);
        $contenu = [];
        foreach ($panier as $id => $qty) {
            $produit = $this->produitRepository->find($id);
            if ($produit) $contenu[] = ['produit' => $produit, 'quantite' => $qty];
        }
        return $contenu;
    }
    public function getTotal(): float {
        return array_sum(array_map(
            fn($i) => $i['produit']->getPrix() * $i['quantite'],
            $this->getContenu()
        ));
    }
    public function getNbArticles(): int {
        return array_sum($this->getSession()->get('panier', []));
    }
    public function clear(): void {
        $this->getSession()->remove('panier');
    }
}
