<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Avis;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    private function downloadImage(string $url, string $destination): ?string
    {
        $dir = dirname($destination);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        try {

            $content = file_get_contents($url);

            if ($content !== false) {

                file_put_contents($destination, $content);

                return basename($destination);
            }

        } catch (\Exception $e) {

            dump('Image failed: ' . $url);
        }

        return null;
    }

    public function load(ObjectManager $manager): void
    {
        $catDir  = __DIR__ . '/../../public/uploads/categories/';
        $prodDir = __DIR__ . '/../../public/uploads/produits/';

        // ── CATEGORIES ─────────────────────────────────────────
        $categoriesData = [
            [
                'nom'         => 'Chiens',
                'description' => 'Tout pour vos chiens',
                'imageUrl'    => 'https://images.pexels.com/photos/1108099/pexels-photo-1108099.jpeg?auto=compress&w=400',
                'imageFile'   => 'chiens.jpg',
            ],
            [
                'nom'         => 'Chats',
                'description' => 'Tout pour vos chats',
                'imageUrl'    => 'https://images.pexels.com/photos/45201/kitty-cat-kitten-pet-45201.jpeg?auto=compress&w=400',
                'imageFile'   => 'chats.jpg',
            ],
            [
                'nom'         => 'Lapins',
                'description' => 'Tout pour vos lapins',
                'imageUrl'    => 'https://images.pexels.com/photos/326012/pexels-photo-326012.jpeg?auto=compress&w=400',
                'imageFile'   => 'lapins.jpg',
            ],
            [
                'nom'         => 'Oiseaux',
                'description' => 'Tout pour vos oiseaux',
                'imageUrl'    => 'https://images.pexels.com/photos/56733/pexels-photo-56733.jpeg?auto=compress&w=400',
                'imageFile'   => 'oiseaux.jpg',
            ],
            [
                'nom'         => 'Reptiles',
                'description' => 'Tout pour vos reptiles',
                'imageUrl'    => 'https://images.pexels.com/photos/674318/pexels-photo-674318.jpeg?auto=compress&w=400',
                'imageFile'   => 'reptiles.jpg',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $cat = new Categorie();
            $cat->setNom($data['nom']);
            $cat->setDescription($data['description']);
            $this->downloadImage($data['imageUrl'], $catDir . $data['imageFile']);
            $cat->setImage($data['imageFile']);
            $cat->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($cat);
            $categories[] = $cat;
        }

        // ── PRODUITS ───────────────────────────────────────────
        $produitsData = [
            // Chiens
            [
                'nom'   => 'Croquettes Premium Chien Adulte',
                'desc'  => 'Croquettes de haute qualité pour chiens adultes, riches en protéines.',
                'prix'  => 29.99, 'stock' => 50, 'cat' => 0,
                'imageUrl'  => 'https://images.pexels.com/photos/6568461/pexels-photo-6568461.jpeg?auto=compress&w=400',
                'imageFile' => 'croquettes-chien.jpg',
            ],
            [
                'nom'   => 'Collier Anti-Puce Chien',
                'desc'  => 'Collier répulsif contre les puces et tiques, efficace 8 mois.',
                'prix'  => 14.99, 'stock' => 30, 'cat' => 0,
                'imageUrl'  => 'https://images.pexels.com/photos/4587991/pexels-photo-4587991.jpeg?auto=compress&w=400',
                'imageFile' => 'collier-chien.jpg',
            ],
            [
                'nom'   => 'Laisse Rétractable 5m',
                'desc'  => 'Laisse rétractable robuste jusqu\'à 50kg, bouton de blocage.',
                'prix'  => 19.99, 'stock' => 25, 'cat' => 0,
                'imageUrl'  => 'https://images.pexels.com/photos/1254140/pexels-photo-1254140.jpeg?auto=compress&w=400',
                'imageFile' => 'laisse-chien.jpg',
            ],
            [
                'nom'   => 'Jouet Corde Chien',
                'desc'  => 'Jouet en corde naturelle pour chiens, parfait pour jouer.',
                'prix'  => 8.99, 'stock' => 40, 'cat' => 0,
                'imageUrl'  => 'https://images.pexels.com/photos/3299906/pexels-photo-3299906.jpeg?auto=compress&w=400',
                'imageFile' => 'jouet-chien.jpg',
            ],
            // Chats
            [
                'nom'   => 'Croquettes Chat Stérilisé',
                'desc'  => 'Croquettes adaptées aux chats stérilisés, contrôle du poids.',
                'prix'  => 24.99, 'stock' => 45, 'cat' => 1,
                'imageUrl'  => 'https://images.pexels.com/photos/6957107/pexels-photo-6957107.jpeg?auto=compress&w=400',
                'imageFile' => 'croquettes-chat.jpg',
            ],
            [
                'nom'   => 'Arbre à Chat 150cm',
                'desc'  => 'Arbre à chat avec griffoir, hamac et perchoir, stable et solide.',
                'prix'  => 89.99, 'stock' => 10, 'cat' => 1,
                'imageUrl'  => 'https://images.pexels.com/photos/1955134/pexels-photo-1955134.jpeg?auto=compress&w=400',
                'imageFile' => 'arbre-chat.jpg',
            ],
            [
                'nom'   => 'Litière Végétale 10L',
                'desc'  => 'Litière végétale ultra-absorbante, sans poussière, écologique.',
                'prix'  => 12.99, 'stock' => 60, 'cat' => 1,
                'imageUrl'  => 'https://images.pexels.com/photos/4587955/pexels-photo-4587955.jpeg?auto=compress&w=400',
                'imageFile' => 'litiere-chat.jpg',
            ],
            [
                'nom'   => 'Souris Interactive Chat',
                'desc'  => 'Jouet souris interactive avec mouvement automatique pour stimuler.',
                'prix'  => 15.99, 'stock' => 35, 'cat' => 1,
                'imageUrl'  => 'https://images.pexels.com/photos/2061057/pexels-photo-2061057.jpeg?auto=compress&w=400',
                'imageFile' => 'jouet-chat.jpg',
            ],
            // Lapins
            [
                'nom'   => 'Granulés Lapin Adulte 3kg',
                'desc'  => 'Granulés complets pour lapins adultes, riche en fibres.',
                'prix'  => 11.99, 'stock' => 40, 'cat' => 2,
                'imageUrl'  => 'https://images.pexels.com/photos/326012/pexels-photo-326012.jpeg?auto=compress&w=400',
                'imageFile' => 'granules-lapin.jpg',
            ],
            [
                'nom'   => 'Cage Lapin Grand Espace',
                'desc'  => 'Grande cage pour lapin avec bac facile à nettoyer.',
                'prix'  => 59.99, 'stock' => 8, 'cat' => 2,
                'imageUrl' => 'https://images.pexels.com/photos/2886214/pexels-photo-2886214.jpeg?auto=compress&w=400',
                'imageFile' => 'cage-lapin.jpg',
            ],
            [
                'nom'   => 'Foin de Prairie 1kg',
                'desc'  => 'Foin naturel de haute qualité pour lapins et rongeurs.',
                'prix'  => 6.99, 'stock' => 70, 'cat' => 2,
                'imageUrl'  => 'https://images.pexels.com/photos/1458916/pexels-photo-1458916.jpeg?auto=compress&w=400',
                'imageFile' => 'foin-lapin.jpg',
            ],
            // Oiseaux
            [
                'nom'   => 'Graines Perruches 1kg',
                'desc'  => 'Mélange de graines naturelles pour perruches et petits oiseaux.',
                'prix'  => 7.99, 'stock' => 55, 'cat' => 3,
                'imageUrl'  => 'https://images.pexels.com/photos/56733/pexels-photo-56733.jpeg?auto=compress&w=400',
                'imageFile' => 'graines-oiseaux.jpg',
            ],
            [
                'nom'   => 'Cage Perroquet XL',
                'desc'  => 'Grande cage chromée pour perroquet avec accessoires inclus.',
                'prix'  => 129.99, 'stock' => 5, 'cat' => 3,
                'imageUrl'  => 'https://images.pexels.com/photos/4056462/pexels-photo-4056462.jpeg?auto=compress&w=400',
                'imageFile' => 'cage-perroquet.jpg',
            ],
            [
                'nom'   => 'Perchoir Naturel Bois',
                'desc'  => 'Perchoir en bois naturel pour oiseaux, plusieurs tailles.',
                'prix'  => 9.99, 'stock' => 30, 'cat' => 3,
                'imageUrl'  => 'https://images.pexels.com/photos/1661179/pexels-photo-1661179.jpeg?auto=compress&w=400',
                'imageFile' => 'perchoir-oiseau.jpg',
            ],
            // Reptiles
            [
                'nom'   => 'Terrarium Verre 60x30',
                'desc'  => 'Terrarium en verre avec ventilation pour lézards et serpents.',
                'prix'  => 79.99, 'stock' => 7, 'cat' => 4,
                'imageUrl'  => 'https://images.pexels.com/photos/674318/pexels-photo-674318.jpeg?auto=compress&w=400',
                'imageFile' => 'terrarium.jpg',
            ],
            [
                'nom'   => 'Lampe Chauffante Reptile',
                'desc'  => 'Lampe infrarouge chauffante pour terrarium, 100W.',
                'prix'  => 18.99, 'stock' => 20, 'cat' => 4,
                'imageUrl'  => 'https://images.pexels.com/photos/4099354/pexels-photo-4099354.jpeg?auto=compress&w=400',
                'imageFile' => 'lampe-reptile.jpg',
            ],
            [
                'nom'   => 'Substrat Coco Reptile 5L',
                'desc'  => 'Substrat fibre de coco naturel pour reptiles tropicaux.',
                'prix'  => 13.99, 'stock' => 25, 'cat' => 4,
                'imageUrl'  => 'https://images.pexels.com/photos/3299906/pexels-photo-3299906.jpeg?auto=compress&w=400',
                'imageFile' => 'substrat-reptile.jpg',
            ],
            [
                'nom'   => 'Insectes Séchés Reptile',
                'desc'  => 'Mélange d\'insectes séchés pour reptiles insectivores.',
                'prix'  => 16.99, 'stock' => 15, 'cat' => 4,
                'imageUrl'  => 'https://images.pexels.com/photos/37833/rainbow-lorikeet-parrots-australia-rainbow-37833.jpeg?auto=compress&w=400',
                'imageFile' => 'insectes-reptile.jpg',
            ],
        ];
        $produits = [];
        foreach ($produitsData as $data) {
            $produit = new Produit();
            $produit->setNom($data['nom']);
            $produit->setDescription($data['desc']);
            $produit->setPrix((string) $data['prix']);
            $produit->setStock($data['stock']);
            $produit->setCategorie($categories[$data['cat']]);
            $produit->setCreatedAt(new \DateTimeImmutable());
            $produit->setUpdatedAt(new \DateTimeImmutable());
            $this->downloadImage($data['imageUrl'], $prodDir . $data['imageFile']);
            $produit->setImage($data['imageFile']);
            $manager->persist($produit);
            $produits[] = $produit;
        }

        // ── ADMIN USER ─────────────────────────────────────────
        $admin = new User();
        $admin->setNom('Admin');
        $admin->setPrenom('SymPet');
        $admin->setEmail('admin@sympet.fr');
        $admin->setPassword($this->hasher->hashPassword($admin, 'Admin1234!'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);
        $admin->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($admin);

        // ── NORMAL USER ────────────────────────────────────────
        $user = new User();
        $user->setNom('Dupont');
        $user->setPrenom('Jean');
        $user->setEmail('jean@sympet.fr');
        $user->setPassword($this->hasher->hashPassword($user, 'User1234!'));
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user);

        $manager->flush();

        // ── AVIS ─────────────────────────────────────────────
        $avisData = [
            ['note' => 5, 'commentaire' => 'Excellent produit, mon chien adore !'],
            ['note' => 4, 'commentaire' => 'Très bonne qualité et livraison rapide.'],
            ['note' => 5, 'commentaire' => 'Parfait pour mon chat, je recommande fortement.'],
            ['note' => 3, 'commentaire' => 'Produit correct mais un peu cher.'],
            ['note' => 4, 'commentaire' => 'Bonne qualité générale et solide.'],
            ['note' => 5, 'commentaire' => 'Incroyable, exactement comme décrit.'],
        ];

        foreach ($avisData as $index => $data) {
            $avis = new Avis();

            $avis->setNote($data['note']);
            $avis->setCommentaire($data['commentaire']);
            $avis->setCreatedAt(new \DateTimeImmutable());

            // alternate between admin and user
            $avis->setUser($index % 2 === 0 ? $admin : $user);

            // random product
            $avis->setProduit($produits[$index]);

            $manager->persist($avis);
        }
        // ── COMMANDES ────────────────────────────────────────

        for ($i = 1; $i <= 5; $i++) {

            $commande = new Commande();

            $commande->setNumeroCommande('CMD-' . strtoupper(uniqid()));
            $commande->setStatut(['En attente', 'Payée', 'Expédiée'][rand(0, 2)]);
            $commande->setCreatedAt(new \DateTimeImmutable());

            // alternate users
            $commande->setUser($i % 2 === 0 ? $admin : $user);

            $total = 0;

            // 2 products per order
            for ($j = 0; $j < 2; $j++) {

                $produit = $produits[array_rand($produits)];

                $ligne = new LigneCommande();

                $quantite = rand(1, 3);

                $ligne->setProduit($produit);
                $ligne->setQuantite($quantite);
                $ligne->setPrixUnitaire($produit->getPrix());

                $ligne->setCommande($commande);

                $total += $quantite * (float)$produit->getPrix();

                $manager->persist($ligne);

                $commande->addLigneCommande($ligne);
            }

            $commande->setTotal((string)$total);

            $manager->persist($commande);
        }


        $manager->flush();
    }
}
