<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../config/config.php';
ini_set('default_charset', 'UTF-8');
header('Content-Type: application/json; charset=utf-8');

// Vérifie que les dates sont fournies
if (isset($_GET['date_debut'], $_GET['date_fin'])) {
    $date_debut = $_GET['date_debut'];
    $date_fin = $_GET['date_fin'];
    $capacite = $_GET['capacite'] ?? null;
    $chaine = $_GET['chaine'] ?? null;
    $categorie = $_GET['categorie'] ?? null;
    $prix = $_GET['prix'] ?? null;
    $vue = $_GET['vue'] ?? null;
    $extensible = $_GET['extensible'] ?? null;

    $sql = "
    SELECT c.*, h.nom AS nom_hotel, h.categorie AS categorie_hotel
    FROM chambre c
    JOIN hotel h ON c.hotel_id = h.hotel_id
    WHERE NOT EXISTS (
        SELECT 1 FROM associe a
        JOIN reservation r ON a.reservation_id = r.reservation_id
        WHERE a.chambre_id = c.chambre_id
        AND r.etat IN ('confirme', 'enregistre')
        AND daterange(r.date_debut, r.date_fin, '[]') && daterange($1::date, $2::date, '[]')
    )
    ";

    $params = [$date_debut, $date_fin];

    // Filtres supplémentaires
    if (!empty($capacite)) {
        $sql .= " AND c.capacite = $" . (count($params) + 1);
        $params[] = $capacite;
    }

    if (!empty($chaine)) {
        $sql .= " AND h.chaine_id = $" . (count($params) + 1);
        $params[] = $chaine;
    }

    if (!empty($categorie)) {
        $sql .= " AND h.categorie = $" . (count($params) + 1);
        $params[] = $categorie;
    }

    if (!empty($prix)) {
        $sql .= " AND c.prix <= $" . (count($params) + 1);
        $params[] = $prix;
    }

    if (!empty($vue)) {
        $sql .= " AND c.vue = $" . (count($params) + 1);
        $params[] = $vue;
    }

    if ($extensible !== null && $extensible !== "") {
        $sql .= " AND c.extensible = $" . (count($params) + 1);
        $params[] = $extensible;
    }

    // Exécution
    $result = pg_query_params($conn, $sql, $params);

    $chambres = [];
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $chambres[] = $row;
        }        
    } else {
        echo json_encode(["error" => pg_last_error($conn)]);
        exit();
    }

    echo json_encode($chambres);
} else {
    echo json_encode(["error" => "Les dates de début et de fin sont obligatoires."]);
}
?>