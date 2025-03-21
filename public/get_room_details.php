<?php
include '../config/config.php';
header('Content-Type: application/json');

if (!isset($_GET['chambre_id'])) {
    echo json_encode(["error" => "ID de chambre manquant."]);
    exit();
}

$chambre_id = $_GET['chambre_id'];

$sql = "
    SELECT 
        c.commodites, c.prix, c.capacite, c.vue, c.extensible, c.problemes,
        h.nom AS hotel_nom, h.adresse AS hotel_adresse, h.categorie AS hotel_categorie
    FROM chambre c
    JOIN hotel h ON c.hotel_id = h.hotel_id
    WHERE c.chambre_id = $1
";

$result = pg_query_params($conn, $sql, [$chambre_id]);

if ($result && pg_num_rows($result) > 0) {
    echo json_encode(pg_fetch_assoc($result));
} else {
    echo json_encode(["error" => "Aucune information trouvée pour cette chambre."]);
}
?>