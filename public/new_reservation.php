<?php
session_start();
include '../config/config.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'client') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Récupérer les informations du client
$sql_client = "SELECT nas FROM client WHERE email = $1";
$result_client = pg_query_params($conn, $sql_client, array($_SESSION['email']));

if ($result_client && pg_num_rows($result_client) > 0) {
    $client = pg_fetch_assoc($result_client);
    
    // Debug output
    echo "<pre>";
    print_r($client);
    echo "</pre>";

    if (isset($client['nas'])) {
        $client_NAS = $client['nas'];
        echo "NAS récupéré: " . htmlspecialchars($client_NAS); // Debugging output
    } else {
        die("❌ Erreur: Clé 'nas' non trouvée dans la réponse SQL.");
    }
} else {
    die("❌ Erreur: Aucun client trouvé avec cet email.");
}






// Récupérer la liste des hôtels
$sql_hotels = "SELECT hotel_ID, nom FROM hotel";
$result_hotels = pg_query($conn, $sql_hotels);

// Gestion du formulaire
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotel_ID = $_POST['hotel_ID'];
    $chambre_ID = $_POST['chambre_ID'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Vérifier que les dates sont valides
    if ($date_debut >= $date_fin) {
        $error = "❌ La date de fin doit être après la date de début.";
    } else {
        // Insérer la réservation
        $sql_insert = "INSERT INTO reservation (date_debut, date_fin, etat, paiement, client_NAS) 
                       VALUES ($1, $2, 'En attente', 0, $3) RETURNING reservation_ID";
        $params = array($date_debut, $date_fin, $client_NAS);
        $result_insert = pg_query_params($conn, $sql_insert, $params);

        if ($row = pg_fetch_assoc($result_insert)) {
            $reservation_ID = $row['reservation_ID'];

            // Associer la réservation à la chambre choisie
            $sql_associe = "INSERT INTO associe (chambre_ID, reservation_ID) VALUES ($1, $2)";
            pg_query_params($conn, $sql_associe, array($chambre_ID, $reservation_ID));

            header("Location: view_reservations.php");
            exit();
        } else {
            $error = "❌ Erreur lors de la réservation.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Réservation - eHôtels</title>
    <link rel="stylesheet" href="css/style.css">
    
    <script>
        function filterRooms() {
            let hotel = document.getElementById("hotel").value;
            let prix = document.getElementById("prix").value;
            let capacite = document.getElementById("capacite").value;
            let vue = document.getElementById("vue").value;
            let problemes = document.getElementById("problemes").value;
            let extensible = document.getElementById("extensible").checked ? 1 : 0;

            fetch("get_rooms.php?hotel=" + hotel + "&prix=" + prix + "&capacite=" + capacite + "&vue=" + vue + "&problemes=" + problemes + "&extensible=" + extensible)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("room-list").innerHTML = data;
                });
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Faire une Nouvelle Réservation</h2>

        <!-- FILTER SECTION -->
        <div class="filter-section">
            <div id="room-list">
            <p>Sélectionnez un hôtel et ajustez les filtres pour voir les chambres disponibles.</p>
        </div>

            <label for="hotel">Hôtel :</label>
            <select id="hotel" onchange="filterRooms()">
                <option value="">Tous</option>
                <?php
                $sql_hotels = "SELECT hotel_ID, nom FROM hotel";
                $result_hotels = pg_query($conn, $sql_hotels);
                while ($hotel = pg_fetch_assoc($result_hotels)) {
                    echo "<option value='" . $hotel['hotel_ID'] . "'>" . htmlspecialchars($hotel['nom']) . "</option>";
                }
                ?>
            </select>

            <div style="display: flex; gap: 20px; align-items: center; justify-content: space-between; flex-wrap: wrap; width: 100%;">

    <!-- Prix Max -->
    <div style="display: flex; align-items: center; flex: 1;">
        <label for="prix" style="margin-right: 4px;">Prix Max :</label>
        <span style="font-weight: bold;">$</span>
        <input type="number" id="prix" placeholder="Prix max" oninput="filterRooms()" style="width: 100px; margin-left: 5px;">
    </div>

    <!-- Capacité -->
    <div style="display: flex; align-items: center; flex: 1;">
        <label for="capacite" style="margin-right: 5px;">Capacité :</label>
        <input type="number" id="capacite" placeholder="Capacité min" oninput="filterRooms()" style="width: 100px;">
    </div>

    <!-- Extensible -->
    <div style="display: flex; align-items: center; flex: 1;">
        <label for="extensible" style="margin-right: 1px;">Extensible :</label>
        <input type="checkbox" id="extensible" onclick="filterRooms()">
    </div>

</div>


<!-- Vue Selection (should remain below) -->
<label for="vue">Vue :</label>
<select id="vue" onchange="filterRooms()">
    <option value="">Toutes</option>
    <option value="Mer">Mer</option>
    <option value="Jardin">Jardin</option>
    <option value="Ville">Ville</option>
</select>

        
        
        <!-- FORM FOR BOOKING -->
        <form method="POST" action="">
            <input type="hidden" name="chambre_ID" id="selectedRoom" required>
            <label for="date_debut">Date de Début :</label>
            <input type="date" name="date_debut" required>

            <label for="date_fin">Date de Fin :</label>
            <input type="date" name="date_fin" required>

            <button type="submit">Réserver</button>
        </form>

        <a href="client_dashboard.php">Retour au tableau de bord</a>
    </div>
</body>
</html>
