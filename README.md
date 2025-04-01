# eHotels - Projet CSI2532

Bienvenue sur le projet **eHotels**, une application web permettant de gérer des réservations d'hôtels.

## Prérequis

Avant de commencer, assurez-vous d'avoir les logiciels suivants installés :

- **PHP** (≥8.x)
- **PostgreSQL** (≥13.x)
- **Git**

## Installation du Projet

**1. Cloner le dépôt GitHub**

Cloner le dépôt depuis ce répo GitHub.

**2. Créer la base de données dans PostgreSQL**

- Ouvrez **pgAdmin** ou **psql**
- **Exécutez les scripts SQL** pour créer les tables et insérer des données pour les fichiers `database/schema.sql` et `database/data.sql`.

```sh
psql -U postgres -f database/schema.sql
psql -U postgres -f database/data.sql
```

**3. Configuration de la Connexion à la Base de Données**

Aller voir le fichier `config_example.php` et suivre les instructions présentes.

**4. Lancer le Site Web**

Ouvrez un terminal et placez-vous dans le dossier `public/`

Lancez le serveur intégré de PHP :
```sh
php -S localhost:8000
```

**5. Ouvrez votre navigateur et accédez au site :**

```
http://localhost:8000/
```

## Informations sur l'application

Il existe deux types d'utilisateurs sur le site : **Client** et **Employé**.

### 🔹 Accès au site Client
- Créez un nouveau compte via le formulaire d’inscription.
- Connectez-vous avec ce compte pour effectuer des réservations.

### 🔹 Accès au site Employé
- Utilisez les identifiants suivants :
  - **Courriel :** jlavoie@ehotels.com  
  - **Mot de passe :** Manager123
- Il s'agit d'un compte employé général pour accéder à toutes les fonctionnalités liées à la gestion des réservations.
- Ce compte permet de voir et modifier toutes les réservations de la compagnie eHôtels.

## Auteurs
- Jack Ogilvie #300351466
- Matthew Roger #300368930


## Licence
Projet réalisé dans le cadre du cours **CSI2532 - Bases de Données**.