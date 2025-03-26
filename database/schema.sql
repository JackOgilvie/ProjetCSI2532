-- Supprime la base de données si elle existe déjà
DROP DATABASE IF EXISTS eHotels;

-- Création de la base de données
CREATE DATABASE eHotels;
\c eHotels;

-- Activer l'extension nécessaire pour les comparaisons de dates
CREATE EXTENSION IF NOT EXISTS btree_gist;

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
    chaine_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    nombre_hotels INT NOT NULL CHECK (nombre_hotels > 0)
);

-- Création de la table `hotel`
CREATE TABLE hotel (
    hotel_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    nombre_chambres INT NOT NULL CHECK (nombre_chambres > 0),
    categorie INT NOT NULL CHECK (categorie BETWEEN 1 AND 5),
    chaine_id INT NOT NULL,
    FOREIGN KEY (chaine_id) REFERENCES chaine(chaine_id) ON DELETE CASCADE
);

-- Création de la table `chambre`
CREATE TABLE chambre (
    chambre_id SERIAL PRIMARY KEY,
    prix DECIMAL(10,2) NOT NULL CHECK (prix > 0),
    commodites TEXT NOT NULL,
    capacite INT NOT NULL CHECK (capacite > 0),
    vue VARCHAR(255) NOT NULL,
    problemes TEXT,
    extensible BOOLEAN NOT NULL,
    hotel_id INT NOT NULL,
    FOREIGN KEY (hotel_id) REFERENCES hotel(hotel_id) ON DELETE CASCADE
);

-- Création de la table `client`
CREATE TABLE client (
    nas BIGINT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe TEXT NOT NULL,
    adresse TEXT NOT NULL,
    date_enregistrement DATE NOT NULL
);

-- Création de la table `reservation`
CREATE TABLE reservation (
    reservation_id SERIAL PRIMARY KEY,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    etat VARCHAR(50) NOT NULL CHECK (etat IN ('en_attente', 'confirme', 'annule', 'enregistre')),
    paiement DECIMAL(10,2) NOT NULL CHECK (paiement >= 0) DEFAULT 0,
    client_NAS BIGINT NOT NULL,
    FOREIGN KEY (client_nas) REFERENCES client(nas) ON DELETE SET NULL,
    CHECK (date_debut < date_fin)
);

-- Création de la table `employe`
CREATE TABLE employe (
    employe_id SERIAL PRIMARY KEY,
    nas BIGINT NOT NULL UNIQUE,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    adresse TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe TEXT NOT NULL,
    position VARCHAR(100) NOT NULL CHECK (position IN ('gestionnaire', 'receptionniste', 'menage', 'securite')),
    hotel_id INT NOT NULL,
    FOREIGN KEY (hotel_id) REFERENCES hotel(hotel_id) ON DELETE CASCADE
);

-- Assurer qu'il y a un seul Manager par hôtel
CREATE UNIQUE INDEX unique_manager_per_hotel ON employe (hotel_id) WHERE position = 'gestionnaire';

-- Création de la table `gere` (Relation Employé - Réservation)
CREATE TABLE gere (
    employe_id INT,
    reservation_id INT,
    PRIMARY KEY (employe_id, reservation_id),
    FOREIGN KEY (employe_id) REFERENCES employe(employe_id) ON DELETE CASCADE,
    FOREIGN KEY (reservation_id) REFERENCES reservation(reservation_id) ON DELETE CASCADE
);

-- Création de la table `associe` (Relation Chambre - Réservation)
CREATE TABLE associe (
    chambre_id INT,
    reservation_id INT,
    PRIMARY KEY (chambre_id, reservation_id),
    FOREIGN KEY (chambre_id) REFERENCES chambre(chambre_id) ON DELETE CASCADE,
    FOREIGN KEY (reservation_id) REFERENCES reservation(reservation_id) ON DELETE CASCADE
);




-- Ajout des Triggers pour la base de données


-- Empêcher la double réservation d'une même chambre sur les mêmes dates
CREATE OR REPLACE FUNCTION check_reservation_conflict()
RETURNS TRIGGER AS $$
DECLARE
    debut DATE;
    fin DATE;
BEGIN
    -- On récupère les dates de la réservation liée
    SELECT date_debut, date_fin INTO debut, fin
    FROM reservation
    WHERE reservation_id = NEW.reservation_id;

    -- Vérification de conflit de dates pour la même chambre
    IF EXISTS (
        SELECT 1
        FROM reservation r
        JOIN associe a ON r.reservation_id = a.reservation_id
        WHERE a.chambre_id = NEW.chambre_id
        AND r.etat IN ('confirme', 'enregistre')
        AND daterange(r.date_debut, r.date_fin, '[]') && daterange(debut, fin, '[]')
    ) THEN
        RAISE EXCEPTION 'Cette chambre est deja reservee pour ces dates.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- On réattache le trigger à la table associe
CREATE TRIGGER prevent_double_booking
BEFORE INSERT ON associe
FOR EACH ROW
EXECUTE FUNCTION check_reservation_conflict();


-- Empêcher de faire un paiement qui n'est pas le bon montant
CREATE OR REPLACE FUNCTION verify_payment_before_confirmation()
RETURNS TRIGGER AS $$
DECLARE
    prix_total DECIMAL(10,2);
BEGIN
    -- Récupérer le prix total des chambres associées à la réservation
    SELECT SUM(c.prix) INTO prix_total
    FROM chambre c
    JOIN associe a ON c.chambre_id = a.chambre_id
    WHERE a.reservation_id = NEW.reservation_id;

    -- Vérifier si le paiement est suffisant
    IF NEW.paiement < prix_total THEN
        RAISE EXCEPTION 'Le paiement est insuffisant pour confirmer la réservation.';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER check_payment
BEFORE UPDATE ON reservation
FOR EACH ROW
WHEN (NEW.etat = 'confirme')
EXECUTE FUNCTION verify_payment_before_confirmation();






-- Index pour améliorer la performance des requêtes SQL

-- Accélère la recherche des réservations par date
CREATE INDEX idx_reservation_date ON reservation (date_debut, date_fin);

-- Optimise la recherche des chambres par hôtel et capacité
CREATE INDEX idx_chambre_hotel_capacity ON chambre (hotel_id, capacite);

-- Améliore la recherche des employés par position
CREATE INDEX idx_employe_position ON employe (position);

-- Accélère l'affichage des réservations pour un client donné
CREATE INDEX idx_reservation_client ON reservation (client_NAS, date_debut);




-- Ajout des deux vues spécifiques

-- Vue de chambres disponibles par zone
CREATE OR REPLACE VIEW chambres_disponibles_par_zone AS
SELECT 
    h.adresse AS zone,
    COUNT(*) AS chambres_disponibles
FROM chambre c
JOIN hotel h ON c.hotel_id = h.hotel_id
WHERE NOT EXISTS (
    SELECT 1
    FROM associe a
    JOIN reservation r ON a.reservation_id = r.reservation_id
    WHERE a.chambre_id = c.chambre_id
    AND r.etat IN ('en_attente', 'confirme', 'enregistre')
    AND daterange(r.date_debut, r.date_fin, '[]') && daterange(CURRENT_DATE, CURRENT_DATE, '[]')
)
GROUP BY h.adresse
ORDER BY h.adresse;

-- Vue de capacité de chambres d'un hotel spécifique
CREATE OR REPLACE VIEW capacite_chambres_par_hotel AS
SELECT 
    h.hotel_id,
    h.nom AS nom_hotel,
    c.chambre_id,
    c.capacite
FROM chambre c
JOIN hotel h ON c.hotel_id = h.hotel_id
ORDER BY h.nom, c.chambre_id;