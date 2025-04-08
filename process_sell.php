<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et nettoyer les données du formulaire
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
    $adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING);
    $poids = filter_input(INPUT_POST, 'poids', FILTER_VALIDATE_FLOAT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);

    // Calculer le montant
    $montant = $poids * 100; // 100 FCFA par kg

    // Stocker les informations dans la session
    $_SESSION['sell_info'] = [
        'nom' => $nom,
        'telephone' => $telephone,
        'adresse' => $adresse,
        'poids' => $poids,
        'description' => $description,
        'date' => $date,
        'montant' => $montant
    ];

    // Rediriger vers la page de confirmation
    header('Location: confirmation_vente.php');
    exit();
} else {
    // Si quelqu'un essaie d'accéder directement à cette page
    header('Location: participer.php');
    exit();
}
?> 