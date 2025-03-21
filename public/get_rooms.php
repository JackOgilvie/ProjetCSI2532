<?php
include '../config/config.php';

if (isset($_GET['hotel_ID'])) {
    $hotel_ID = $_GET['hotel_ID'];
    $prix_max = isset($_GET['prix_max']) ? $_GET['prix_max'] : null;
    $capacite = isset($_GET['capacite']) ? $_GET['capacite'] : null;
    $vue = isset($_GET['vue']) ? $_GET['vue'] : null;
    $extensible = isset($_GET['extensible']) ? $_GET['extensible'] : null;

    // Build query dynamically
    $sql = "SELECT chambre_ID, commodites, prix FROM chambre WHERE hotel_ID = $1";
    $params = [$hotel_ID];

    if (!empty($prix_max)) {
        $sql .= " AND prix <= $" . (count($params) + 1);
        $params[] = $prix_max;
    }
    if (!empty($capacite)) {
        $sql .= " AND capacite >= $" . (count($params) + 1);
        $params[] = $capacite;
    }
    if (!empty($vue) && $vue !== "Toutes") {
        $sql .= " AND vue = $" . (count($params) + 1);
        $params[] = $vue;
    }
    if ($extensible !== null) {
        $sql .= " AND extensible = $" . (count($params) + 1);
        $params[] = $extensible;
    }

    $result = pg_query_params($conn, $sql, $params);

    echo "<option value=''>Sélectionnez une chambre</option>";
    while ($row = pg_fetch_assoc($result)) {
        echo "<option value='" . $row['chambre_ID'] . "'>" . htmlspecialchars($row['commodites']) . " - " . $row['prix'] . "€</option>";
    }
}
?>
