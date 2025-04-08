<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: connexion.php');
    exit();
}

$method = isset($_POST['method']) ? $_POST['method'] : null;

if (!$method) {
    die("Méthode de paiement non spécifiée.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de Paiement - RecycleArt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .navbar {
            height: 80px;
        }
        .navbar-brand img {
            height: 80px;
            margin-top: -10px;
            margin-bottom: -10px;
        }
        .main-content {
            margin-top: 120px;
        }
        .payment-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .payment-method-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #28a745;
        }
        .form-label {
            font-weight: 500;
            margin-top: 1rem;
        }
        .btn-payment {
            background-color: #28a745;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-payment:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="accueil.php">
                <img src="logo.jpg" alt="RecycleArt" height="80" class="d-inline-block align-top" style="margin-top: -10px; margin-bottom: -10px;">
            </a>
            <div class="ms-auto">
                <a class="btn btn-warning position-relative" href="panier.php">
                    <i class="fas fa-shopping-cart me-1"></i>Panier
                    <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo count($_SESSION['panier']); ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </nav>

    <div class="container main-content">
        <div class="payment-form">
            <h1 class="text-center mb-4">Détails de Paiement</h1>
            
            <?php if ($method === 'tmoney'): ?>
                <div class="text-center mb-4">
                    <i class="fas fa-mobile-alt payment-method-icon"></i>
                    <h3>Tmoney</h3>
                </div>
            <?php elseif ($method === 'flooz'): ?>
                <div class="text-center mb-4">
                    <i class="fas fa-wallet payment-method-icon"></i>
                    <h3>Flooz</h3>
                </div>
            <?php elseif ($method === 'ecobank'): ?>
                <div class="text-center mb-4">
                    <i class="fas fa-university payment-method-icon"></i>
                    <h3>Ecobank</h3>
                </div>
            <?php endif; ?>

            <form action="process_payment.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="method" value="<?php echo htmlspecialchars($method); ?>">

                <?php if ($method === 'tmoney'): ?>
                    <div class="mb-3">
                        <label for="tmoney_phone" class="form-label">Numéro Tmoney</label>
                        <input type="text" class="form-control" id="tmoney_phone" name="tmoney_phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="tmoney_password" class="form-label">Code Tmoney</label>
                        <input type="password" class="form-control" id="tmoney_password" name="tmoney_password" required>
                    </div>
                <?php elseif ($method === 'flooz'): ?>
                    <div class="mb-3">
                        <label for="flooz_number" class="form-label">Numéro Flooz</label>
                        <input type="text" class="form-control" id="flooz_number" name="flooz_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="flooz_code" class="form-label">Code Flooz</label>
                        <input type="password" class="form-control" id="flooz_code" name="flooz_code" required>
                    </div>
                <?php elseif ($method === 'ecobank'): ?>
                    <div class="mb-3">
                        <label for="ecobank_account" class="form-label">Numéro de compte Ecobank</label>
                        <input type="text" class="form-control" id="ecobank_account" name="ecobank_account" required>
                    </div>
                    <div class="mb-3">
                        <label for="ecobank_password" class="form-label">Mot de passe Ecobank</label>
                        <input type="password" class="form-control" id="ecobank_password" name="ecobank_password" required>
                    </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-payment">
                        <i class="fas fa-lock me-2"></i>Payer
                    </button>
                    <button type="button" class="btn btn-payment" onclick="history.back()">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation des formulaires
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>