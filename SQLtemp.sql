CREATE TABLE chaine (
    chaine_ID INT PRIMARY KEY,
    nom VARCHAR(255),
    adresse TEXT,
    email VARCHAR(255),
    telephone VARCHAR(50),
    nombre_hotels INT
);

CREATE TABLE hotel (
    hotel_ID INT PRIMARY KEY,
    nom VARCHAR(255),
    adresse TEXT,
    email VARCHAR(255),
    telephone VARCHAR(50),
    nombre_chambres INT,
    categorie VARCHAR(50),
    chaine_ID INT,
    FOREIGN KEY (chaine_ID) REFERENCES chaine(chaine_ID)
);

CREATE TABLE chambre (
    chambre_ID INT PRIMARY KEY,
    prix DECIMAL(10,2),
    commodites TEXT,
    capacite INT,
    vue VARCHAR(255),
    problemes TEXT,
    extensible BOOLEAN,
    hotel_ID INT,
    FOREIGN KEY (hotel_ID) REFERENCES hotel(hotel_ID)
);

CREATE TABLE client (
    NAS INT PRIMARY KEY,
    nom VARCHAR(255),
    prénom VARCHAR(255),
    adresse TEXT,
    date_enregistrement DATE
);

CREATE TABLE reservation (
    reservation_ID INT PRIMARY KEY,
    date_debut DATE,
    date_fin DATE,
    etat VARCHAR(50),
    client_NAS INT,
    FOREIGN KEY (client_NAS) REFERENCES client(NAS)
);

CREATE TABLE employe (
    employe_ID INT PRIMARY KEY,
    NAS INT,
    nom VARCHAR(255),
    prénom VARCHAR(255),
    adresse TEXT,
    rôle VARCHAR(100),
    hotel_ID INT,
    FOREIGN KEY (hotel_ID) REFERENCES hotel(hotel_ID)
);




CREATE TABLE gere (
    employe_ID INT,
    reservation_ID INT,
    PRIMARY KEY (employe_ID, reservation_ID),
    FOREIGN KEY (employe_ID) REFERENCES employe(employe_ID),
    FOREIGN KEY (reservation_ID) REFERENCES reservation(reservation_ID)
);

CREATE TABLE associe (
    chambre_ID INT,
    reservation_ID INT,
    PRIMARY KEY (chambre_ID, reservation_ID),
    FOREIGN KEY (chambre_ID) REFERENCES chambre(chambre_ID),
    FOREIGN KEY (reservation_ID) REFERENCES reservation(reservation_ID)
);
