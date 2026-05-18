# SymPet 🐾

SymPet est une application web développée avec Symfony permettant la gestion d’une animalerie en ligne.  
Le projet permet de gérer les catégories, produits, commandes, avis clients et utilisateurs à travers une interface moderne et intuitive.

---

# 🚀 Fonctionnalités

- Gestion des utilisateurs
- Authentification et rôles (Admin / User)
- Gestion des catégories
- Gestion des produits
- Gestion des commandes
- Gestion des lignes de commande
- Gestion des avis clients
- Interface responsive

---

# 🛠️ Technologies utilisées

- PHP 8
- Symfony 6
- Doctrine ORM
- MySQL
- Twig
- Bootstrap

---

# ⚙️ Installation

## 1. Cloner le projet

```bash
git clone https://github.com/your-username/sympet.git
cd sympet
```

---

## 2. Installer les dépendances

```bash
composer install
```

---

## 3. Configurer le fichier `.env`

Modifier les informations de connexion MySQL :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/sympet"
```

---

## 4. Créer la base de données

```bash
php bin/console doctrine:database:create
```

---

## 5. Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate
```

---

## 6. Charger les fixtures

```bash
php bin/console doctrine:fixtures:load
```

---

# ▶️ Lancer le serveur

```bash
symfony server:start
```

Ou :

```bash
php -S localhost:8000 -t public
```

---

# 👤 Comptes de démonstration

## Admin

```text
Email: admin@sympet.fr
Password: Admin1234!
```

## Utilisateur

```text
Email: jean@sympet.fr
Password: User1234!
```

---

# 📊 Diagramme de classes

<img width="731" height="450" alt="sympet (1)" src="https://github.com/user-attachments/assets/23b1a9c1-4c8d-4924-ac83-e0f5d35e18ad" />

---

# 🗄️ Entités principales

- User
- Produit
- Categorie
- Commande
- LigneCommande
- Avis

---

# 📸 Images

Les images des produits et catégories sont stockées dans :

```bash
public/uploads/
```

---

# 📌 Auteur

Projet réalisé par Mohamed Habib Slimen

---

# 📄 Licence

Projet académique / éducatif.
