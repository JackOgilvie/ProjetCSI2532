-- Fichier contenant des requêtes SQL utiles pour le site web eHotels

-- Annuler une réservation sans la supprimer
UPDATE reservation 
SET etat = 'Annulé' 
WHERE reservation_ID = 5;

-- Insérer une nouvelle réservation pour un client
INSERT INTO reservation (date_debut, date_fin, etat, paiement, client_NAS)
VALUES ('2025-06-15', '2025-06-20', 'En attente', 450, 101223456);

-- Mettre à jour le prix des chambres d'un hôtel donné
UPDATE chambre
SET prix = prix * 1.15  -- Augmente le prix de 15%
WHERE hotel_ID = 3 AND prix IS NOT NULL;

-- Trouver toutes les réservations d'un client spécifique
SELECT r.reservation_ID, r.date_debut, r.date_fin, r.etat, r.paiement, h.nom AS hotel_nom
FROM reservation r
JOIN client c ON r.client_NAS = c.NAS
JOIN associe a ON r.reservation_ID = a.reservation_ID
JOIN chambre ch ON a.chambre_ID = ch.chambre_ID
JOIN hotel h ON ch.hotel_ID = h.hotel_ID
WHERE c.NAS = 101223456;

-- Vérifier les chambres disponibles dans un hôtel spécifique
SELECT c.chambre_ID, c.prix, c.capacite, c.commodites, c.vue
FROM chambre c
LEFT JOIN associe a ON c.chambre_ID = a.chambre_ID
LEFT JOIN reservation r ON a.reservation_ID = r.reservation_ID 
    AND r.etat IN ('Confirmé', 'Enregistré')
WHERE c.hotel_ID = 2 AND r.reservation_ID IS NULL;

-- Supprimer une réservation ancienne de plus de 2 ans (historique de réservation obsolète)
DELETE FROM reservation
WHERE date_fin < NOW() - INTERVAL '2 years' AND etat = 'Annulé';