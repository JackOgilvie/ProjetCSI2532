# eHotels - Projet CSI2532

Bienvenue sur le projet **eHotels**, une application web permettant de gérer des réservations d'hôtels, développée en **PHP, PostgreSQL et Apache**.

## Prérequis

Avant de commencer, assurez-vous d'avoir les logiciels suivants installés :

- **PHP** (≥8.x)
- **PostgreSQL** (≥13.x)
- **Git**

## Installation du Projet

**1. Cloner le dépôt GitHub :**
Cloner le dépôt depuis ce répo GitHub.

**2. Créer la base de données dans PostgreSQL :**
- Ouvrez **pgAdmin** ou **psql**
- **Exécutez les scripts SQL** pour créer les tables et insérer des données :
```sh
psql -U postgres -f database/schema.sql
psql -U postgres -f database/data.sql
```

## Configuration de la Connexion à la Base de Données

Aller voir le fichier `config.example.php` et suivre les instructions présentes.

## Lancer le Site Web

**1. Ouvrez un terminal et placez-vous dans le dossier `public/`** :

**2. Lancez le serveur intégré de PHP** :
```sh
php -S localhost:8000
```

**3. Ouvrez votre navigateur et accédez au site :**
```
http://localhost:8000/
```



## Auteurs
- Jack Ogilvie #300351466
- Matthew Roger #300368930


## Licence
Projet réalisé dans le cadre du cours **CSI2532 - Bases de Données**.