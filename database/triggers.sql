-- no overlapping reserv meme chambre
CREATE OR REPLACE FUNCTION check_reservation_conflict()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM associe a
        JOIN reservation r ON a.reservation_ID = r.reservation_ID
        WHERE a.chambre_ID = NEW.chambre_ID
        AND r.etat IN ('Confirmé', 'Enregistré')
        AND daterange(r.date_debut, r.date_fin, '[]') && daterange(NEW.date_debut, NEW.date_fin, '[]')
    ) THEN
        RAISE EXCEPTION 'Cette chambre est déjà réservée pour ces dates.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_double_booking
BEFORE INSERT ON associe
FOR EACH ROW
EXECUTE FUNCTION check_reservation_conflict();



--convert reservation to rental
CREATE OR REPLACE FUNCTION convert_reservation_to_rental()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.etat = 'Enregistré' THEN
        -- Keep history: Reservation stays but is marked as 'Enregistré'
        INSERT INTO gere (employe_ID, reservation_ID)
        VALUES (1, NEW.reservation_ID);
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER reservation_to_rental
AFTER UPDATE ON reservation
FOR EACH ROW
WHEN (NEW.etat = 'Enregistré')
EXECUTE FUNCTION convert_reservation_to_rental();
