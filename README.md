# 🚀 Coding Tool Box – Guide d'installation

Bienvenue dans **Coding Tool Box**, un outil complet de gestion pédagogique conçu pour la Coding Factory.  
Ce projet Laravel inclut la gestion des groupes, promotions, étudiants, rétro (Kanban), QCM  dynamiques, et bien plus.

---

## 📦 Prérequis

Assurez-vous d’avoir les éléments suivants installés sur votre machine :

- PHP ≥ 8.1
- Composer
- MySQL ou MariaDB
- Node.js + npm (pour les assets frontend si nécessaire)
- Laravel CLI (`composer global require laravel/installer`)

---

## ⚙️ Installation du projet

Exécutez les étapes ci-dessous pour lancer le projet en local :

### 1. Cloner le dépôt

```bash
git clone https://m_thibaud@bitbucket.org/m_thibaud/projet-web-2025.git
cd coding-tool-box
cp .env.example .env
```

### 2. Configuration de l'environnement

```bash
✍️ Ouvrez le fichier .env et configurez les paramètres liés à votre base de données :

DB_DATABASE=nom_de_votre_bdd
DB_USERNAME=utilisateur
DB_PASSWORD=motdepasse
```

### 3. Installation des dépendances PHP

```bash
composer install
```

### 4. Nettoyage et optimisation du cache

```bash
php artisan optimize:clear
```

### 5. Génération de la clé d'application

```bash
php artisan key:generate
```

### 6. Migration de la base de données

```bash
php artisan migrate
```

### 7. Population de la base (Données de test)

```bash
php artisan db:seed
```

---

## 💻 Compilation des assets (si nécessaire)

```bash
npm install
npm run dev
```

---

## 👤 Comptes de test disponibles

| Rôle       | Email                         | Mot de passe |
|------------|-------------------------------|--------------|
| **Admin**  | admin@codingfactory.com       | 123456       |
| Enseignant | teacher@codingfactory.com     | 123456       |
| Étudiant   | student@codingfactory.com     | 123456       |

---

## 🚧 Fonctionnalités principales

- 🔧 Gestion des groupes, promotions, étudiants
- 📅 Vie commune avec système de pointage
- 📊 Bilans semestriels étudiants via QCM générés par IA
- 🧠 Génération automatique de QCM par langage sélectionné
- ✅ Système de Kanban pour les rétrospectives
- 📈 Statistiques d’usage et suivi pédagogique

---

## 🧑‍💻 Fonctionnalités développées par Axel (Sprint 1 – 2025)

Cette section présente les **User Stories** que j’ai prises en charge pour le premier sprint du projet **Coding Tool Box**. Elles couvrent les fondations de la gestion utilisateur et des entités principales.

### 1️⃣ Dashboard Admin – Vue d’ensemble

**En tant qu’** administrateur  
**Je veux** un tableau de bord avec une vue d'ensemble des promotions, étudiants, enseignants et groupes  
**Afin de** pouvoir accéder rapidement à la gestion des entités principales.

✅ **Critères d’acceptation** :
- Le dashboard affiche les totaux de promotions, étudiants, enseignants et groupes.
- Le nombre de groupes est statique pour ce sprint (non géré dynamiquement).

---

### 2️⃣ Dashboard Enseignant – Mes promotions

**En tant qu’** enseignant  
**Je veux** voir mes promotions assignées  
**Afin de** gérer et suivre les performances des étudiants.

✅ **Critères d’acceptation** :
- Liste des promotions qui sont associées au professeur visibles depuis la page “Promotions”.
- Récapitulatif des promotions en cours sur la page dashboard (overview).
- Une table relationnelle lie enseignants et promotions (relation N:N).

---

### 3️⃣ Gestion des étudiants

**En tant qu’** administrateur  
**Je veux** créer, modifier et supprimer des étudiants  
**Afin de** gérer les membres de chaque promotion.

✅ **Critères d’acceptation** :
- Création d’un étudiant avec : nom, prénom, date de naissance, email.
- Mot de passe généré automatiquement et envoyé par mail.
- Modification et suppression via modals avec requêtes AJAX (sans rechargement de page).
- Association à une promotion au moment de la création/modification.

---

### 4️⃣ Gestion des promotions

**En tant qu’** administrateur  
**Je veux** créer, modifier et supprimer des promotions  
**Afin de** structurer les groupes d’étudiants.

✅ **Critères d’acceptation** :
- Création d’une promotion avec nom + infos de base.
- Modification et suppression via modals + AJAX.

---

### 5️⃣ Gestion des enseignants

**En tant qu’** administrateur  
**Je veux** créer, modifier et supprimer des enseignants  
**Afin de** gérer les intervenants pédagogiques.

✅ **Critères d’acceptation** :
- Informations minimales : nom, prénom, email.
- Modification/suppression via modals + AJAX.

---

### 6️⃣ Gestion du profil utilisateur

**En tant qu’** utilisateur  
**Je veux** modifier mes infos personnelles (email, mot de passe, photo) et supprimer mon compte  
**Afin de** personnaliser et sécuriser mon expérience.

✅ **Critères d’acceptation** :
- Modification de l’email et du mot de passe.
- Téléversement d’une photo de profil.
- Suppression du compte.
---
