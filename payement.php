<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: connexion.php');
    exit();
}

// Calculer le total du panier
$total = 0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $price = isset($item['price']) ? (float)$item['price'] : 0;
        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
        $total += $price * $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - RecycleArt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .navbar {
            height: 60px;
        }
        .navbar-brand img {
            height: 80px;
            margin-top: -10px;
            margin-bottom: -10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="accueil.php">
                <img src="logo.jpg" alt="RecycleArt" height="60" class="d-inline-block align-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="accueil.php">
                            <i class="fas fa-home me-1"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="fas fa-info-circle me-1"></i>À propos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">
                            <i class="fas fa-shopping-bag me-1"></i>Articles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-success me-2" href="participer.php">
                            <i class="fas fa-hand-holding-heart me-1"></i>Je veux participer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning position-relative" href="panier.php">
                            <i class="fas fa-shopping-cart me-1"></i>Panier
                            <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo count($_SESSION['panier']); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu de la page -->
    <div class="container main-content"  >
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Choisissez votre moyen de paiement</h1>
                        
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Total à payer : <strong><?php echo number_format($total, 0, ',', ' '); ?> FCFA</strong>
                        </div>

                        <form action="saisie.php" method="POST" class="payment-form">
                            <div class="payment-methods">
                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="method" id="tmoney" value="tmoney" required>
                                    <label class="form-check-label d-flex align-items-center" for="tmoney">
                                        <i class="fas fa-mobile-alt me-3 text-primary fa-2x"></i>
                                        <div>
                                            <strong>TMoney</strong>
                                            <br>
                                            <small class="text-muted">Paiement mobile rapide et sécurisé</small>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="method" id="flooz" value="flooz">
                                    <label class="form-check-label d-flex align-items-center" for="flooz">
                                        <i class="fas fa-wallet me-3 text-warning fa-2x"></i>
                                        <div>
                                            <strong>Flooz</strong>
                                            <br>
                                            <small class="text-muted">Paiement mobile simple et pratique</small>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check mb-4 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="method" id="ecobank" value="ecobank">
                                    <label class="form-check-label d-flex align-items-center" for="ecobank">
                                        <i class="fas fa-university me-3 text-success fa-2x"></i>
                                        <div>
                                            <strong>Ecobank</strong>
                                            <br>
                                            <small class="text-muted">Paiement bancaire sécurisé</small>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-lock me-2"></i>
                                    Continuer
                                </button>
                                <a href="panier.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Retour au panier
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des options de paiement
        document.querySelectorAll('.form-check').forEach(method => {
            method.addEventListener('click', function() {
                this.querySelector('input[type="radio"]').checked = true;
            });
        });
    </script>
</body>
</html>