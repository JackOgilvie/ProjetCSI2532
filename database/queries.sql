--delete reservation(keep in history)
DELETE FROM reservation WHERE reservation_ID = 5;

-- Insert a new reservation
INSERT INTO reservation (date_debut, date_fin, etat, paiement, client_NAS)
VALUES ('2025-04-10', '2025-04-15', 'En attente', 500, 101223456);

-- Update room price by increasing it by 10% for a specific category
UPDATE chambre
SET prix = prix * 1.10
WHERE hotel_ID = 1 AND capacite = 2;



--finds rooms not reserved
SELECT c.chambre_ID, c.prix, c.capacite, c.commodites, c.vue
FROM chambre c
WHERE c.hotel_ID = 1
AND NOT EXISTS (
    SELECT 1 
    FROM associe a
    JOIN reservation r ON a.reservation_ID = r.reservation_ID
    WHERE a.chambre_ID = c.chambre_ID
    AND r.etat IN ('Confirmé', 'Enregistré')
);
