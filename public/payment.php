<?php
session_start();
$reservation_id = $_GET['reservation_id'] ?? null;

if (!$reservation_id) {
    die("❌ Aucune réservation fournie.");
}
?>

<h2>Page de Paiement</h2>
<p>Merci pour votre réservation ! Votre numéro est : <?= htmlspecialchars($reservation_id) ?></p>

<!-- Ajouter ici vos options de paiement -->
