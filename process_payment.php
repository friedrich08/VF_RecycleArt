<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: connexion.php');
    exit();
}

$message = '';
$isValid = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['method'] ?? null;

    if (!$method) {
        die("Méthode de paiement non spécifiée.");
    }

    // Validation en fonction de la méthode
    switch ($method) {
        case 'tmoney':
            $phone = $_POST['tmoney_phone'] ?? null;
            $password = $_POST['tmoney_password'] ?? null;
            
            if (empty($phone) || empty($password)) {
                $message = "Veuillez remplir tous les champs.";
            } elseif (!preg_match('/^(90|91|92|93)\d{6}$/', $phone)) {
                $message = "Numéro de téléphone Tmoney invalide. Il doit comporter 8 chiffres et commencer par 90, 91, 92 ou 93.";
            } elseif (strlen($password) < 4) {
                $message = "Le code Tmoney doit contenir au moins 4 caractères.";
            } else {
                $isValid = true;
            }
            break;

        case 'flooz':
            $phone = $_POST['flooz_number'] ?? null;
            $code = $_POST['flooz_code'] ?? null;
            
            if (empty($phone) || empty($code)) {
                $message = "Veuillez remplir tous les champs.";
            } elseif (!preg_match('/^(95|96|97|98|99)\d{6}$/', $phone)) {
                $message = "Numéro de téléphone Flooz invalide. Il doit comporter 8 chiffres et commencer par 95, 96, 97, 98 ou 99.";
            } elseif (strlen($code) < 4) {
                $message = "Le code Flooz doit contenir au moins 4 caractères.";
            } else {
                $isValid = true;
            }
            break;

        case 'ecobank':
            $accountNumber = $_POST['ecobank_account'] ?? null;
            $password = $_POST['ecobank_password'] ?? null;
            
            if (empty($accountNumber) || empty($password)) {
                $message = "Veuillez remplir tous les champs.";
            } elseif (!preg_match('/^\d{14}$/', $accountNumber)) {
                $message = "Numéro de compte Ecobank invalide. Il doit comporter 14 chiffres.";
            } elseif (strlen($password) < 6) {
                $message = "Le mot de passe doit contenir au moins 6 caractères.";
            } else {
                $isValid = true;
            }
            break;

        default:
            $message = "Moyen de paiement non pris en charge.";
            break;
    }

    if ($isValid) {
        // Simule le traitement du paiement
        $_SESSION['payment_message'] = "Paiement réussi avec le mode $method.";
        $_SESSION['mode_de_paiement'] = $method;
        
        // Affichage du loader
        echo '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Traitement du Paiement - RecycleArt</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background-color: #f8f9fa;
                }
                .loader-container {
                    text-align: center;
                    padding: 2rem;
                    background-color: white;
                    border-radius: 10px;
                    box-shadow: 0 0 20px rgba(0,0,0,0.1);
                }
                .spinner {
                    width: 3rem;
                    height: 3rem;
                    margin-bottom: 1rem;
                }
                .message {
                    font-size: 1.1rem;
                    color: #6c757d;
                }
            </style>
        </head>
        <body>
            <div class="loader-container">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="message">Veuillez patienter, nous traitons votre paiement...</p>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "result.php";
                }, 2000);
            </script>
        </body>
        </html>';
        exit();
    } else {
        $_SESSION['payment_message'] = $message;
        header('Location: payement.php');
        exit();
    }
}
?>