


--Speeds up room availability searches
CREATE INDEX idx_reservation_date ON reservation (date_debut);



--Makes filtering rooms by hotel & size faster  

CREATE INDEX idx_chambre_hotel_capacity ON chambre (hotel_ID, capacite);


--Quickly finds hotel managers
CREATE INDEX idx_employe_role ON employe (rôle);
