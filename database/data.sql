-- Se connecter à la base de donnees
\c eHotels;

-- Insertion des Chaînes Hôtelières
INSERT INTO chaine (nom, adresse, email, telephone, nombre_hotels) VALUES
('Northern Comfort', 'Toronto, Canada', 'contact@northerncomfort.com', '+1 416 555 1234', 8),
('Urban Retreats', 'New York, USA', 'info@urbanretreats.com', '+1 212 777 5678', 8),
('Sunset Resorts', 'Miami, USA', 'reservations@sunsetresorts.com', '+1 305 888 9999', 8),
('Mountain Lodges', 'Vancouver, Canada', 'info@mountainlodges.com', '+1 604 222 3333', 8),
('Caribbean Escapes', 'Nassau, Bahamas', 'bookings@caribbeanescapes.com', '+1 242 999 1111', 8);

-- Insertion des Hôtels
INSERT INTO hotel (nom, adresse, email, telephone, nombre_chambres, categorie, chaine_id) VALUES
('Toronto Skyline Hotel', 'Toronto Skyline Hotel, Toronto, Canada', 'torontoskylinehotel@northerncomfort.com', '+2 574 661 6181', 42, 5, 1),
('Vancouver Grand Lodge', 'Vancouver Grand Lodge, Toronto, Canada', 'vancouvergrandlodge@northerncomfort.com', '+1 441 925 9798', 89, 3, 1),
('Montreal Central Stay', 'Montreal Central Stay, Toronto, Canada', 'montrealcentralstay@northerncomfort.com', '+5 102 891 7509', 49, 3, 1),
('Calgary Mountain Retreat', 'Calgary Mountain Retreat, Toronto, Canada', 'calgarymountainretreat@northerncomfort.com', '+4 335 614 9176', 61, 3, 1),
('Ottawa River Inn', 'Ottawa River Inn, Toronto, Canada', 'ottawariverinn@northerncomfort.com', '+3 240 690 2283', 42, 5, 1),
('Banff Alpine Lodge', 'Banff Alpine Lodge, Toronto, Canada', 'banffalpinelodge@northerncomfort.com', '+3 881 517 3727', 43, 5, 1),
('Quebec City Charm Hotel', 'Quebec City Charm Hotel, Toronto, Canada', 'quebeccitycharmhotel@northerncomfort.com', '+1 168 625 9999', 57, 3, 1),
('Whistler Snow Resort', 'Whistler Snow Resort, Toronto, Canada', 'whistlersnowresort@northerncomfort.com', '+2 265 210 4336', 67, 4, 1),
('New York Empire Hotel', 'New York Empire Hotel, New York, USA', 'newyorkempirehotel@urbanretreats.com', '+1 905 627 5966', 72, 4, 2),
('Los Angeles Beach View', 'Los Angeles Beach View, New York, USA', 'losangelesbeachview@urbanretreats.com', '+5 508 430 2623', 66, 4, 2),
('Chicago Skyline Suites', 'Chicago Skyline Suites, New York, USA', 'chicagoskylinesuites@urbanretreats.com', '+1 802 387 2562', 99, 5, 2),
('San Francisco Bay Resort', 'San Francisco Bay Resort, New York, USA', 'sanfranciscobayresort@urbanretreats.com', '+4 494 925 1437', 71, 5, 2),
('Miami Ocean Breeze', 'Miami Ocean Breeze, New York, USA', 'miamioceanbreeze@urbanretreats.com', '+6 367 315 2925', 50, 3, 2),
('Las Vegas Luxury Stay', 'Las Vegas Luxury Stay, New York, USA', 'lasvegasluxurystay@urbanretreats.com', '+7 568 990 5983', 49, 3, 2),
('Seattle Rainforest Inn', 'Seattle Rainforest Inn, New York, USA', 'seattlerainforestinn@urbanretreats.com', '+4 519 284 7121', 77, 3, 2),
('Houston Urban Comfort', 'Houston Urban Comfort, New York, USA', 'houstonurbancomfort@urbanretreats.com', '+6 756 685 1544', 56, 4, 2),
('Nassau Blue Lagoon', 'Nassau Blue Lagoon, Miami, USA', 'nassaubluelagoon@sunsetresorts.com', '+3 220 309 8780', 97, 3, 3),
('Montego Bay Paradise', 'Montego Bay Paradise, Miami, USA', 'montegobayparadise@sunsetresorts.com', '+4 954 901 5967', 100, 3, 3),
('Punta Cana Beachfront', 'Punta Cana Beachfront, Miami, USA', 'puntacanabeachfront@sunsetresorts.com', '+9 780 585 4548', 68, 4, 3),
('Cancun White Sands', 'Cancun White Sands, Miami, USA', 'cancunwhitesands@sunsetresorts.com', '+9 347 727 3921', 98, 3, 3),
('Aruba Sunset Resort', 'Aruba Sunset Resort, Miami, USA', 'arubasunsetresort@sunsetresorts.com', '+8 679 908 7063', 59, 5, 3),
('Bermuda Ocean Pearl', 'Bermuda Ocean Pearl, Miami, USA', 'bermudaoceanpearl@sunsetresorts.com', '+1 261 708 9466', 41, 5, 3),
('St. Lucia Palm Villas', 'St. Lucia Palm Villas, Miami, USA', 'st.luciapalmvillas@sunsetresorts.com', '+7 534 218 1066', 55, 3, 3),
('Turks & Caicos Hideaway', 'Turks & Caicos Hideaway, Miami, USA', 'turks&caicoshideaway@sunsetresorts.com', '+2 680 975 4952', 43, 5, 3),
('Boston Harbor Hotel', 'Boston Harbor Hotel, Vancouver, Canada', 'bostonharborhotel@mountainlodges.com', '+8 896 406 9434', 49, 3, 4),
('Denver Rocky Stay', 'Denver Rocky Stay, Vancouver, Canada', 'denverrockystay@mountainlodges.com', '+4 212 514 5444', 93, 4, 4),
('Phoenix Desert Oasis', 'Phoenix Desert Oasis, Vancouver, Canada', 'phoenixdesertoasis@mountainlodges.com', '+5 558 249 2849', 42, 4, 4),
('Atlanta Skyline Suites', 'Atlanta Skyline Suites, Vancouver, Canada', 'atlantaskylinesuites@mountainlodges.com', '+9 758 435 3001', 57, 4, 4),
('Orlando Family Resort', 'Orlando Family Resort, Vancouver, Canada', 'orlandofamilyresort@mountainlodges.com', '+7 373 271 1833', 85, 4, 4),
('Dallas Lone Star Inn', 'Dallas Lone Star Inn, Vancouver, Canada', 'dallaslonestarinn@mountainlodges.com', '+6 586 433 6830', 81, 4, 4),
('San Diego Sunset Lodge', 'San Diego Sunset Lodge, Vancouver, Canada', 'sandiegosunsetlodge@mountainlodges.com', '+5 714 902 8909', 91, 3, 4),
('Anchorage Northern Lights Lodge', 'Anchorage Northern Lights Lodge, Vancouver, Canada', 'anchoragenorthernlightslodge@mountainlodges.com', '+9 678 158 7598', 40, 3, 4),
('Nassau Paradise Cove', 'Nassau, Bahamas', 'nassauparadisecove@caribbeanescapes.com', '+1 242 555 0001', 70, 5, 5),
('Montego Bay Grand Resort', 'Montego Bay, Jamaica', 'montegobaygrandresort@caribbeanescapes.com', '+1 876 555 2222', 85, 4, 5),
('Punta Cana Royal Villas', 'Punta Cana, Dominican Republic', 'puntacanaroyalvillas@caribbeanescapes.com', '+1 809 555 3333', 90, 5, 5),
('Barbados Ocean Retreat', 'Bridgetown, Barbados', 'barbadosoceanretreat@caribbeanescapes.com', '+1 246 555 4444', 75, 4, 5),
('St. Thomas Serenity Resort', 'St. Thomas, US Virgin Islands', 'stthomasserenity@caribbeanescapes.com', '+1 340 555 5555', 65, 4, 5),
('Turks & Caicos Luxury Stay', 'Providenciales, Turks & Caicos', 'turkscaicosluxury@caribbeanescapes.com', '+1 649 555 6666', 80, 5, 5),
('Antigua Breeze Resort', 'St. John''s, Antigua', 'antiguabreeze@caribbeanescapes.com', '+1 268 555 7777', 72, 3, 5),
('Cayman Island Bay Resort', 'Grand Cayman, Cayman Islands', 'caymanbayresort@caribbeanescapes.com', '+1 345 555 8888', 88, 4, 5);

-- Insertion des Chambres
INSERT INTO chambre (prix, commodites, capacite, vue, problemes, extensible, hotel_id) VALUES
(169.84, 'WiFi, TV, Terrasse', 2, 'Vue sur jardin', NULL, FALSE, 1),
(262.1, 'WiFi, TV, Jacuzzi', 4, 'Vue sur montagne', NULL, FALSE, 1),
(269.76, 'WiFi, TV, Piscine privee', 3, 'Vue sur montagne', 'Problème d''eclairage', TRUE, 1),
(332.39, 'WiFi, TV, Terrasse', 2, 'Vue sur mer', NULL, TRUE, 1),
(389.09, 'WiFi, TV, Climatisation', 1, 'Vue sur ville', 'Telecommande TV defectueuse', FALSE, 1),
(213.79, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', 'Serrure endommagee', FALSE, 2),
(131.81, 'WiFi, TV, Climatisation', 1, 'Vue sur mer', 'Bruits provenant de la ventilation', FALSE, 2),
(300.71, 'WiFi, TV, Mini-bar', 5, 'Vue sur montagne', 'Douche à faible pression', FALSE, 2),
(388.14, 'WiFi, TV, Climatisation', 3, 'Vue sur ville', 'Rideaux dechires', TRUE, 2),
(301.84, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', 'Fenêtre qui ne s''ouvre pas', FALSE, 2),
(247.09, 'WiFi, TV, Climatisation', 1, 'Vue sur montagne', NULL, TRUE, 3),
(206.65, 'WiFi, TV, Piscine privee', 4, 'Vue sur jardin', 'Évier bouche', FALSE, 3),
(161.72, 'WiFi, TV, Terrasse', 3, 'Vue sur lac', 'Moquette tachee', TRUE, 3),
(146.11, 'WiFi, TV, Piscine privee', 4, 'Vue sur mer', 'Meubles rayes', TRUE, 3),
(265.41, 'WiFi, TV, Jacuzzi', 2, 'Vue sur lac', 'Porte difficile à fermer', TRUE, 3),
(114.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur ville', 'Fuite dans la salle de bain', TRUE, 4),
(241.43, 'WiFi, TV, Mini-bar', 3, 'Vue sur montagne', 'Bruits d''aeration', TRUE, 4),
(320.73, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', NULL, FALSE, 4),
(135.37, 'WiFi, TV, Mini-bar', 1, 'Vue sur mer', 'Problème de serrure electronique', FALSE, 4),
(219.77, 'WiFi, TV, Climatisation', 4, 'Vue sur jardin', 'Television ne s''allume pas', FALSE, 4),
(100.0, 'WiFi, TV, Terrasse', 1, 'Vue sur jardin', NULL, FALSE, 21),
(115.0, 'WiFi, TV, Jacuzzi', 2, 'Vue sur montagne', 'Bruit de ventilation', TRUE, 22),
(130.0, 'WiFi, TV, Piscine privee', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 23),
(145.0, 'WiFi, TV, Mini-bar', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 24),
(160.0, 'WiFi, TV, Terrasse', 5, 'Vue sur lac', 'Meubles abîmes', TRUE, 25),
(175.0, 'WiFi, TV, Jacuzzi', 1, 'Vue sur jardin', NULL, FALSE, 26),
(190.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 27),
(205.0, 'WiFi, TV, Mini-bar', 3, 'Vue sur mer', 'Serrure defectueuse', TRUE, 28),
(220.0, 'WiFi, TV, Terrasse', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 29),
(235.0, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 30),
(250.0, 'WiFi, TV, Piscine privee', 1, 'Vue sur jardin', NULL, TRUE, 31),
(265.0, 'WiFi, TV, Mini-bar', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 32),
(280.0, 'WiFi, TV, Terrasse', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 33),
(295.0, 'WiFi, TV, Jacuzzi', 4, 'Vue sur ville', 'Rideaux dechires', TRUE, 34),
(310.0, 'WiFi, TV, Piscine privee', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 35),
(325.0, 'WiFi, TV, Mini-bar', 1, 'Vue sur jardin', NULL, FALSE, 36),
(340.0, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', 'Bruit de ventilation', TRUE, 37),
(355.0, 'WiFi, TV, Jacuzzi', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 38),
(370.0, 'WiFi, TV, Piscine privee', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 39),
(385.0, 'WiFi, TV, Mini-bar', 5, 'Vue sur lac', 'Meubles abîmes', TRUE, 40),
(100.0, 'WiFi, TV, Terrasse', 1, 'Vue sur jardin', NULL, FALSE, 1),
(115.0, 'WiFi, TV, Jacuzzi', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 2),
(130.0, 'WiFi, TV, Piscine privee', 3, 'Vue sur mer', 'Serrure defectueuse', TRUE, 3),
(145.0, 'WiFi, TV, Mini-bar', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 4),
(160.0, 'WiFi, TV, Terrasse', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 5),
(175.0, 'WiFi, TV, Jacuzzi', 1, 'Vue sur jardin', NULL, TRUE, 6),
(190.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 7),
(205.0, 'WiFi, TV, Mini-bar', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 8),
(220.0, 'WiFi, TV, Terrasse', 4, 'Vue sur ville', 'Rideaux dechires', TRUE, 9),
(235.0, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', NULL, FALSE, 10),
(250.0, 'WiFi, TV, Piscine privee', 1, 'Vue sur jardin', NULL, FALSE, 11),
(265.0, 'WiFi, TV, Mini-bar', 2, 'Vue sur montagne', 'Bruit de ventilation', TRUE, 12),
(280.0, 'WiFi, TV, Terrasse', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 13),
(295.0, 'WiFi, TV, Jacuzzi', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 14),
(310.0, 'WiFi, TV, Piscine privee', 5, 'Vue sur lac', 'Meubles abîmes', TRUE, 15),
(325.0, 'WiFi, TV, Mini-bar', 1, 'Vue sur jardin', NULL, FALSE, 16),
(340.0, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 17),
(355.0, 'WiFi, TV, Jacuzzi', 3, 'Vue sur mer', 'Serrure defectueuse', TRUE, 18),
(370.0, 'WiFi, TV, Piscine privee', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 19),
(385.0, 'WiFi, TV, Mini-bar', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 20),
(100.0, 'WiFi, TV, Terrasse', 1, 'Vue sur jardin', NULL, TRUE, 21),
(115.0, 'WiFi, TV, Jacuzzi', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 22),
(130.0, 'WiFi, TV, Piscine privee', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 23),
(145.0, 'WiFi, TV, Mini-bar', 4, 'Vue sur ville', 'Rideaux dechires', TRUE, 24),
(160.0, 'WiFi, TV, Terrasse', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 25),
(175.0, 'WiFi, TV, Jacuzzi', 1, 'Vue sur jardin', NULL, FALSE, 26),
(190.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur montagne', 'Bruit de ventilation', TRUE, 27),
(205.0, 'WiFi, TV, Mini-bar', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 28),
(220.0, 'WiFi, TV, Terrasse', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 29),
(235.0, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', NULL, TRUE, 30),
(250.0, 'WiFi, TV, Piscine privee', 1, 'Vue sur jardin', NULL, FALSE, 31),
(265.0, 'WiFi, TV, Mini-bar', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 32),
(280.0, 'WiFi, TV, Terrasse', 3, 'Vue sur mer', 'Serrure defectueuse', TRUE, 33),
(295.0, 'WiFi, TV, Jacuzzi', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 34),
(310.0, 'WiFi, TV, Piscine privee', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 35),
(325.0, 'WiFi, TV, Mini-bar', 1, 'Vue sur jardin', NULL, TRUE, 36),
(340.0, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 37),
(355.0, 'WiFi, TV, Jacuzzi', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 38),
(370.0, 'WiFi, TV, Piscine privee', 4, 'Vue sur ville', 'Rideaux dechires', TRUE, 39),
(385.0, 'WiFi, TV, Mini-bar', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 40),
(100.0, 'WiFi, TV, Terrasse', 1, 'Vue sur jardin', NULL, FALSE, 1),
(115.0, 'WiFi, TV, Jacuzzi', 2, 'Vue sur montagne', 'Bruit de ventilation', TRUE, 2),
(130.0, 'WiFi, TV, Piscine privee', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 3),
(145.0, 'WiFi, TV, Mini-bar', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 4),
(160.0, 'WiFi, TV, Terrasse', 5, 'Vue sur lac', 'Meubles abîmes', TRUE, 5),
(175.0, 'WiFi, TV, Jacuzzi', 1, 'Vue sur jardin', NULL, FALSE, 6),
(190.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 7),
(205.0, 'WiFi, TV, Mini-bar', 3, 'Vue sur mer', 'Serrure defectueuse', TRUE, 8),
(220.0, 'WiFi, TV, Terrasse', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 9),
(235.0, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 10),
(250.0, 'WiFi, TV, Piscine privee', 1, 'Vue sur jardin', NULL, TRUE, 11),
(265.0, 'WiFi, TV, Mini-bar', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 12),
(280.0, 'WiFi, TV, Terrasse', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 13),
(295.0, 'WiFi, TV, Jacuzzi', 4, 'Vue sur ville', 'Rideaux dechires', TRUE, 14),
(310.0, 'WiFi, TV, Piscine privee', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 15),
(325.0, 'WiFi, TV, Mini-bar', 1, 'Vue sur jardin', NULL, FALSE, 16),
(340.0, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', 'Bruit de ventilation', TRUE, 17),
(355.0, 'WiFi, TV, Jacuzzi', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 18),
(370.0, 'WiFi, TV, Piscine privee', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 19),
(385.0, 'WiFi, TV, Mini-bar', 5, 'Vue sur lac', 'Meubles abîmes', TRUE, 20),
(100.0, 'WiFi, TV, Terrasse', 1, 'Vue sur jardin', NULL, FALSE, 21),
(115.0, 'WiFi, TV, Jacuzzi', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 22),
(130.0, 'WiFi, TV, Piscine privee', 3, 'Vue sur mer', 'Serrure defectueuse', TRUE, 23),
(145.0, 'WiFi, TV, Mini-bar', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 24),
(160.0, 'WiFi, TV, Terrasse', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 25),
(175.0, 'WiFi, TV, Jacuzzi', 1, 'Vue sur jardin', NULL, TRUE, 26),
(190.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 27),
(205.0, 'WiFi, TV, Mini-bar', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 28),
(220.0, 'WiFi, TV, Terrasse', 4, 'Vue sur ville', 'Rideaux dechires', TRUE, 29),
(235.0, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 30),
(250.0, 'WiFi, TV, Piscine privee', 1, 'Vue sur jardin', NULL, FALSE, 31),
(265.0, 'WiFi, TV, Mini-bar', 2, 'Vue sur montagne', 'Bruit de ventilation', TRUE, 32),
(280.0, 'WiFi, TV, Terrasse', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 33),
(295.0, 'WiFi, TV, Jacuzzi', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 34),
(310.0, 'WiFi, TV, Piscine privee', 5, 'Vue sur lac', 'Meubles abîmes', TRUE, 35),
(325.0, 'WiFi, TV, Mini-bar', 1, 'Vue sur jardin', NULL, FALSE, 36),
(340.0, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 37),
(355.0, 'WiFi, TV, Jacuzzi', 3, 'Vue sur mer', 'Serrure defectueuse', TRUE, 38),
(370.0, 'WiFi, TV, Piscine privee', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 39),
(385.0, 'WiFi, TV, Mini-bar', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 40),
(100.0, 'WiFi, TV, Terrasse', 1, 'Vue sur jardin', NULL, TRUE, 1),
(115.0, 'WiFi, TV, Jacuzzi', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 2),
(130.0, 'WiFi, TV, Piscine privee', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 3),
(145.0, 'WiFi, TV, Mini-bar', 4, 'Vue sur ville', 'Rideaux dechires', TRUE, 4),
(160.0, 'WiFi, TV, Terrasse', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 5),
(175.0, 'WiFi, TV, Jacuzzi', 1, 'Vue sur jardin', NULL, FALSE, 6),
(190.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur montagne', 'Bruit de ventilation', TRUE, 7),
(205.0, 'WiFi, TV, Mini-bar', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 8),
(220.0, 'WiFi, TV, Terrasse', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 9),
(235.0, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', 'Meubles abîmes', TRUE, 10),
(250.0, 'WiFi, TV, Piscine privee', 1, 'Vue sur jardin', NULL, FALSE, 11),
(265.0, 'WiFi, TV, Mini-bar', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 12),
(280.0, 'WiFi, TV, Terrasse', 3, 'Vue sur mer', 'Serrure defectueuse', TRUE, 13),
(295.0, 'WiFi, TV, Jacuzzi', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 14),
(310.0, 'WiFi, TV, Piscine privee', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 15),
(325.0, 'WiFi, TV, Mini-bar', 1, 'Vue sur jardin', NULL, TRUE, 16),
(340.0, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 17),
(355.0, 'WiFi, TV, Jacuzzi', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 18),
(370.0, 'WiFi, TV, Piscine privee', 4, 'Vue sur ville', 'Rideaux dechires', TRUE, 19),
(385.0, 'WiFi, TV, Mini-bar', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 20),
(100.0, 'WiFi, TV, Terrasse', 1, 'Vue sur jardin', NULL, FALSE, 21),
(115.0, 'WiFi, TV, Jacuzzi', 2, 'Vue sur montagne', 'Bruit de ventilation', TRUE, 22),
(130.0, 'WiFi, TV, Piscine privee', 3, 'Vue sur mer', 'Serrure defectueuse', FALSE, 23),
(145.0, 'WiFi, TV, Mini-bar', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 24),
(160.0, 'WiFi, TV, Terrasse', 5, 'Vue sur lac', 'Meubles abîmes', TRUE, 25),
(175.0, 'WiFi, TV, Jacuzzi', 1, 'Vue sur jardin', NULL, FALSE, 26),
(190.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 27),
(205.0, 'WiFi, TV, Mini-bar', 3, 'Vue sur mer', 'Serrure defectueuse', TRUE, 28),
(220.0, 'WiFi, TV, Terrasse', 4, 'Vue sur ville', 'Rideaux dechires', FALSE, 29),
(235.0, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', 'Meubles abîmes', FALSE, 30),
(250.0, 'WiFi, TV, Piscine privee', 1, 'Vue sur jardin', NULL, TRUE, 31),
(265.0, 'WiFi, TV, Mini-bar', 2, 'Vue sur montagne', 'Bruit de ventilation', FALSE, 32),
(280.0, 'WiFi, TV, Terrasse', 3, 'Vue sur mer', NULL, FALSE, 33),
(295.0, 'WiFi, TV, Jacuzzi', 4, 'Vue sur ville', NULL, TRUE, 34),
(310.0, 'WiFi, TV, Piscine privee', 5, 'Vue sur lac', NULL, FALSE, 35),
(325.0, 'WiFi, TV, Mini-bar', 1, 'Vue sur jardin', NULL, FALSE, 36),
(340.0, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne',NULL, TRUE, 37),
(355.0, 'WiFi, TV, Jacuzzi', 3, 'Vue sur mer', NULL, FALSE, 38),
(370.0, 'WiFi, TV, Piscine privee', 4, 'Vue sur ville', NULL, FALSE, 39),
(385.0, 'WiFi, TV, Mini-bar', 5, 'Vue sur lac', NULL, TRUE, 40),
(100.0, 'WiFi, TV, Terrasse', 1, 'Vue sur jardin', NULL, FALSE, 1),
(115.0, 'WiFi, TV, Jacuzzi', 2, 'Vue sur montagne', NULL, FALSE, 2),
(130.0, 'WiFi, TV, Piscine privee', 3, 'Vue sur mer', NULL, TRUE, 3),
(145.0, 'WiFi, TV, Mini-bar', 4, 'Vue sur ville', NULL, FALSE, 4),
(160.0, 'WiFi, TV, Terrasse', 5, 'Vue sur lac', NULL, FALSE, 5),
(175.0, 'WiFi, TV, Jacuzzi', 1, 'Vue sur jardin', NULL, TRUE, 6),
(190.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur montagne', NULL, FALSE, 7),
(205.0, 'WiFi, TV, Mini-bar', 3, 'Vue sur mer', NULL, FALSE, 8),
(220.0, 'WiFi, TV, Terrasse', 4, 'Vue sur ville', NULL, TRUE, 9),
(235.0, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', NULL, FALSE, 10),
(250.0, 'WiFi, TV, Piscine privee', 1, 'Vue sur jardin', NULL, FALSE, 11),
(265.0, 'WiFi, TV, Mini-bar', 2, 'Vue sur montagne',NULL, TRUE, 12),
(280.0, 'WiFi, TV, Terrasse', 3, 'Vue sur mer', NULL, FALSE, 13),
(295.0, 'WiFi, TV, Jacuzzi', 4, 'Vue sur ville', NULL, FALSE, 14),
(310.0, 'WiFi, TV, Piscine privee', 5, 'Vue sur lac', NULL, TRUE, 15),
(325.0, 'WiFi, TV, Mini-bar', 1, 'Vue sur jardin', NULL, FALSE, 16),
(340.0, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', NULL, FALSE, 17),
(355.0, 'WiFi, TV, Jacuzzi', 3, 'Vue sur mer', NULL, TRUE, 18),
(370.0, 'WiFi, TV, Piscine privee', 4, 'Vue sur ville', NULL, FALSE, 19),
(385.0, 'WiFi, TV, Mini-bar', 5, 'Vue sur lac', NULL, FALSE, 20),
(100.0, 'WiFi, TV, Terrasse', 1, 'Vue sur jardin', NULL, TRUE, 21),
(115.0, 'WiFi, TV, Jacuzzi', 2, 'Vue sur montagne', NULL, FALSE, 22),
(130.0, 'WiFi, TV, Piscine privee', 3, 'Vue sur mer', NULL, FALSE, 23),
(145.0, 'WiFi, TV, Mini-bar', 4, 'Vue sur ville', NULL, TRUE, 24),
(160.0, 'WiFi, TV, Terrasse', 5, 'Vue sur lac', NULL, FALSE, 25),
(175.0, 'WiFi, TV, Jacuzzi', 1, 'Vue sur jardin', NULL, FALSE, 26),
(190.0, 'WiFi, TV, Piscine privee', 2, 'Vue sur montagne', NULL, TRUE, 27),
(205.0, 'WiFi, TV, Mini-bar', 3, 'Vue sur mer', NULL, FALSE, 28),
(220.0, 'WiFi, TV, Terrasse', 4, 'Vue sur ville', NULL, FALSE, 29),
(235.0, 'WiFi, TV, Jacuzzi', 5, 'Vue sur lac', NULL, TRUE, 30),
(250.0, 'WiFi, TV, Piscine privee', 1, 'Vue sur jardin', NULL, FALSE, 31),
(265.0, 'WiFi, TV, Mini-bar', 2, 'Vue sur montagne', NULL, FALSE, 32),
(280.0, 'WiFi, TV, Terrasse', 3, 'Vue sur mer', NULL, TRUE, 33),
(295.0, 'WiFi, TV, Jacuzzi', 4, 'Vue sur ville', NULL, FALSE, 34),
(310.0, 'WiFi, TV, Piscine privee', 5, 'Vue sur lac', NULL, FALSE, 35),
(325.0, 'WiFi, TV, Mini-bar', 1, 'Vue sur jardin', NULL, TRUE, 36),
(340.0, 'WiFi, TV, Terrasse', 2, 'Vue sur montagne', NULL, FALSE, 37),
(355.0, 'WiFi, TV, Jacuzzi', 3, 'Vue sur mer', NULL, FALSE, 38),
(370.0, 'WiFi, TV, Piscine privee', 4, 'Vue sur ville', NULL, TRUE, 39),
(385.0, 'WiFi, TV, Mini-bar', 5, 'Vue sur lac', NULL, FALSE, 40);

-- Insertion des Gestionnaires
INSERT INTO employe (NAS, nom, prenom, email, adresse, mot_de_passe, position, hotel_id) VALUES
(101223456, 'Lavoie', 'Jean-Marc', 'jlavoie@ehotels.com', '123 Queen St, Toronto, Canada', '$2y$10$uTUt2a5mR/B3H9QeFdghOe6pkqBb/HvnK1XyHxtykvLgAorXb9Axm', 'gestionnaire', 1),
(102334567, 'Smith', 'Rebecca', 'rsmith@ehotels.com', '456 5th Avenue, New York, USA', '$2y$10$uTUt2a5mR/B3H9QeFdghOe6pkqBb/HvnK1XyHxtykvLgAorXb9Axm', 'gestionnaire', 9),
(103445678, 'Gonzalez', 'Luis', 'lgonzalez@ehotels.com', '789 Ocean Drive, Miami, USA', '$2y$10$uTUt2a5mR/B3H9QeFdghOe6pkqBb/HvnK1XyHxtykvLgAorXb9Axm', 'gestionnaire', 17),
(104556789, 'Williams', 'Sophie', 'swilliams@ehotels.com', '321 Mountain Rd, Vancouver, Canada', '$2y$10$uTUt2a5mR/B3H9QeFdghOe6pkqBb/HvnK1XyHxtykvLgAorXb9Axm', 'gestionnaire', 25),
(105667890, 'Dupont', 'Antoine', 'adupont@ehotels.com', '654 Paradise Beach, Nassau, Bahamas', '$2y$10$uTUt2a5mR/B3H9QeFdghOe6pkqBb/HvnK1XyHxtykvLgAorXb9Axm', 'gestionnaire', 33);
