<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // ── CATEGORIES ─────────────────────────────────────────
        $categoriesData = [
            ['nom' => 'Chiens',   'description' => 'Tout pour vos chiens'],
            ['nom' => 'Chats',    'description' => 'Tout pour vos chats'],
            ['nom' => 'Lapins',   'description' => 'Tout pour vos lapins'],
            ['nom' => 'Oiseaux',  'description' => 'Tout pour vos oiseaux'],
            ['nom' => 'Reptiles', 'description' => 'Tout pour vos reptiles'],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $cat = new Categorie();
            $cat->setNom($data['nom']);
            $cat->setDescription($data['description']);
            $manager->persist($cat);
            $categories[] = $cat;
        }


        $produitsData = [
            ['nom' => 'Croquettes Premium Chien Adulte', 'desc' => 'Croquettes de haute qualité pour chiens adultes, riches en protéines.', 'prix' => 29.99, 'stock' => 50, 'cat' => 0],
            ['nom' => 'Collier Anti-Puce Chien', 'desc' => 'Collier répulsif contre les puces et tiques, efficace 8 mois.', 'prix' => 14.99, 'stock' => 30, 'cat' => 0],
            ['nom' => 'Laisse Retractable 5m', 'desc' => 'Laisse retractable robuste jusqu\'à 50kg, bouton de blocage.', 'prix' => 19.99, 'stock' => 25, 'cat' => 0],
            ['nom' => 'Jouet Corde Chien', 'desc' => 'Jouet en corde naturelle pour chiens, parfait pour jouer.', 'prix' => 8.99, 'stock' => 40, 'cat' => 0],

            ['nom' => 'Croquettes Chat Stérilisé', 'desc' => 'Croquettes adaptées aux chats stérilisés, contrôle du poids.', 'prix' => 24.99, 'stock' => 45, 'cat' => 1],
            ['nom' => 'Arbre à Chat 150cm', 'desc' => 'Arbre à chat avec griffoir, hamac et perchoir, stable et solide.', 'prix' => 89.99, 'stock' => 10, 'cat' => 1],
            ['nom' => 'Litière Végétale 10L', 'desc' => 'Litière végétale ultra-absorbante, sans poussière, écologique.', 'prix' => 12.99, 'stock' => 60, 'cat' => 1],
            ['nom' => 'Souris Interactive Chat', 'desc' => 'Jouet souris interactive avec mouvement automatique pour stimuler.', 'prix' => 15.99, 'stock' => 35, 'cat' => 1],

            ['nom' => 'Granulés Lapin Adulte 3kg', 'desc' => 'Granulés complets pour lapins adultes, riche en fibres.', 'prix' => 11.99, 'stock' => 40, 'cat' => 2],
            ['nom' => 'Cage Lapin Grand Espace', 'desc' => 'Grande cage pour lapin avec bac facile à nettoyer.', 'prix' => 59.99, 'stock' => 8, 'cat' => 2],
            ['nom' => 'Foin de Prairie 1kg', 'desc' => 'Foin naturel de haute qualité pour lapins et rongeurs.', 'prix' => 6.99, 'stock' => 70, 'cat' => 2],

            ['nom' => 'Graines Perruches 1kg', 'desc' => 'Mélange de graines naturelles pour perruches et petits oiseaux.', 'prix' => 7.99, 'stock' => 55, 'cat' => 3],
            ['nom' => 'Cage Perroquet XL', 'desc' => 'Grande cage chromée pour perroquet avec accessoires inclus.', 'prix' => 129.99, 'stock' => 5, 'cat' => 3],
            ['nom' => 'Perchoir Naturel Bois', 'desc' => 'Perchoir en bois naturel pour oiseaux, plusieurs tailles.', 'prix' => 9.99, 'stock' => 30, 'cat' => 3],

            ['nom' => 'Terrarium Verre 60x30', 'desc' => 'Terrarium en verre avec ventilation pour lézards et serpents.', 'prix' => 79.99, 'stock' => 7, 'cat' => 4],
            ['nom' => 'Lampe Chauffante Reptile', 'desc' => 'Lampe infrarouge chauffante pour terrarium, 100W.', 'prix' => 18.99, 'stock' => 20, 'cat' => 4],
            ['nom' => 'Substrat Coco Reptile 5L', 'desc' => 'Substrat fibre de coco naturel pour reptiles tropicaux.', 'prix' => 13.99, 'stock' => 25, 'cat' => 4],
            ['nom' => 'Insectes Séchés Reptile', 'desc' => 'Mélange d\'insectes séchés pour reptiles insectivores.', 'prix' => 16.99, 'stock' => 15, 'cat' => 4],
        ];

        foreach ($produitsData as $data) {
            $produit = new Produit();
            $produit->setNom($data['nom']);
            $produit->setDescription($data['desc']);
            $produit->setPrix((string) $data['prix']);
            $produit->setStock($data['stock']);
            $produit->setCategorie($categories[$data['cat']]);
            $produit->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($produit);
        }

        $admin = new User();
        $admin->setNom('Admin');
        $admin->setPrenom('SymPet');
        $admin->setEmail('admin@sympet.fr');
        $admin->setPassword($this->hasher->hashPassword($admin, 'Admin1234!'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);
        $admin->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($admin);


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
    }
}
