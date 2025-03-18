-- Supprime la base de données si elle existe déjà
DROP DATABASE IF EXISTS eHotels;

-- Création de la base de données
CREATE DATABASE eHotels;
\c eHotels;

-- Drop tables s'ils existent déjà pour éviter des conflits
DROP TABLE IF EXISTS associe CASCADE;
DROP TABLE IF EXISTS gere CASCADE;
DROP TABLE IF EXISTS employe CASCADE;
DROP TABLE IF EXISTS reservation CASCADE;
DROP TABLE IF EXISTS client CASCADE;
DROP TABLE IF EXISTS chambre CASCADE;
DROP TABLE IF EXISTS hotel CASCADE;
DROP TABLE IF EXISTS chaine CASCADE;

-- Création de la table `chaine`
CREATE TABLE chaine (
    chaine_ID SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    nombre_hotels INT NOT NULL CHECK (nombre_hotels > 0)
);

-- Création de la table `hotel`
CREATE TABLE hotel (
    hotel_ID SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    nombre_chambres INT NOT NULL CHECK (nombre_chambres > 0),
    categorie INT NOT NULL CHECK (categorie BETWEEN 1 AND 5),
    chaine_ID INT NOT NULL,
    FOREIGN KEY (chaine_ID) REFERENCES chaine(chaine_ID) ON DELETE CASCADE
);

-- Création de la table `chambre`
CREATE TABLE chambre (
    chambre_ID SERIAL PRIMARY KEY,
    prix DECIMAL(10,2) NOT NULL CHECK (prix > 0),
    commodites TEXT NOT NULL,
    capacite INT NOT NULL CHECK (capacite > 0),
    vue VARCHAR(255) NOT NULL,
    problemes TEXT,
    extensible BOOLEAN NOT NULL,
    hotel_ID INT NOT NULL,
    FOREIGN KEY (hotel_ID) REFERENCES hotel(hotel_ID) ON DELETE CASCADE
);

-- Création de la table `client`
CREATE TABLE client (
    NAS BIGINT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    adresse TEXT NOT NULL,
    date_enregistrement DATE NOT NULL
);

-- Création de la table `reservation`
CREATE TABLE reservation (
    reservation_ID SERIAL PRIMARY KEY,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    etat VARCHAR(50) NOT NULL CHECK (etat IN ('En attente', 'Confirmé', 'Annulé', 'Enregistré')),
    paiement DECIMAL(10,2) NOT NULL CHECK (paiement >= 0) DEFAULT 0,
    client_NAS BIGINT NOT NULL,
    FOREIGN KEY (client_NAS) REFERENCES client(NAS) ON DELETE SET NULL,
    CHECK (date_debut < date_fin)
);

-- Création de la table `employe`
CREATE TABLE employe (
    employe_ID SERIAL PRIMARY KEY,
    NAS BIGINT NOT NULL UNIQUE,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    adresse TEXT NOT NULL,
    position VARCHAR(100) NOT NULL CHECK (position IN ('Gestionnaire', 'Réceptionniste', 'Ménage', 'Sécurité')),
    hotel_ID INT NOT NULL,
    FOREIGN KEY (hotel_ID) REFERENCES hotel(hotel_ID) ON DELETE CASCADE
);

-- Assurer qu'il y a un seul Manager par hôtel
CREATE UNIQUE INDEX unique_manager_per_hotel ON employe (hotel_ID) WHERE position = 'Gestionnaire';

-- Création de la table `gere` (Relation Employé - Réservation)
CREATE TABLE gere (
    employe_ID INT,
    reservation_ID INT,
    PRIMARY KEY (employe_ID, reservation_ID),
    FOREIGN KEY (employe_ID) REFERENCES employe(employe_ID) ON DELETE CASCADE,
    FOREIGN KEY (reservation_ID) REFERENCES reservation(reservation_ID) ON DELETE CASCADE
);

-- Création de la table `associe` (Relation Chambre - Réservation)
CREATE TABLE associe (
    chambre_ID INT,
    reservation_ID INT,
    PRIMARY KEY (chambre_ID, reservation_ID),
    FOREIGN KEY (chambre_ID) REFERENCES chambre(chambre_ID) ON DELETE CASCADE,
    FOREIGN KEY (reservation_ID) REFERENCES reservation(reservation_ID) ON DELETE CASCADE
);
