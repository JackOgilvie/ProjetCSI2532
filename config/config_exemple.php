<?php
/*
| Étapes :
| 1. Renommer ce fichier "config_exemple.php" en "config.php"
| 2. Remplacer "votre_mot_de_passe" par votre vrai mot de passe PostgreSQL
| 3. Sauvegarder et ne modifier pas ce fichier après
*/

$host = "localhost";
$dbname = "eHotels";
$user = "postgres";
$password = "votre_mot_de_passe"; // Remplacez par votre vrai mot de passe PostgreSQL

// Connexion à PostgreSQL
$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

// Vérification de la connexion
if (!$conn) {
    die("❌ Erreur de connexion : " . pg_last_error());
}

?>
