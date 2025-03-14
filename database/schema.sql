-- Supprime la base de données si elle existe déjà
DROP DATABASE IF EXISTS eHotels;

-- Création de la base de données
CREATE DATABASE eHotels;
\c eHotels;

-- Supprime la table si elle existe déjà
DROP TABLE IF EXISTS chaine CASCADE;

-- Création de la table chaine
CREATE TABLE chaine (
    chaine_ID SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    nombre_hotels INTEGER DEFAULT 0
);
