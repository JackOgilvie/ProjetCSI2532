<?php
include '../config/config.php';

if (isset($_GET['hotel_ID'])) {
    $hotel_ID = $_GET['hotel_ID'];

    $sql = "SELECT chambre_ID, commodites, prix FROM chambre WHERE hotel_ID = $1";
    $result = pg_query_params($conn, $sql, array($hotel_ID));

    echo "<option value=''>Sélectionnez une chambre</option>";
    while ($row = pg_fetch_assoc($result)) {
        echo "<option value='" . $row['chambre_ID'] . "'>" . htmlspecialchars($row['commodites']) . " - " . $row['prix'] . "€</option>";
    }
}
?>
