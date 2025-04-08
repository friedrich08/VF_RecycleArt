<?php
session_start();

// Vérifier si les informations de vente sont présentes
if (!isset($_SESSION['sell_info'])) {
    header('Location: participer.php');
    exit();
}

$info = $_SESSION['sell_info'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Vente - RecycleArt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        .confirmation-card {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .price-tag {
            font-size: 2rem;
            color: #2e7d32;
            font-weight: bold;
        }
        .info-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 5px;
            margin-bottom: 2rem;
        }
        .btn-confirm {
            background-color: #28a745;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-confirm:hover {
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
            <div class="ms-auto d-flex align-items-center">
                <a class="btn btn-outline-primary me-2" href="accueil.php">
                    <i class="fas fa-home me-1"></i>Accueil
                </a>
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
        <div class="confirmation-card">
            <h1 class="text-center mb-4">Confirmation de votre demande de vente</h1>
            
            <div class="text-center mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
            </div>

            <div class="info-section">
                <h3 class="text-center mb-4">Récapitulatif de votre demande</h3>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nom:</strong> <?php echo htmlspecialchars($info['nom']); ?></p>
                        <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($info['telephone']); ?></p>
                        <p><strong>Adresse de collecte:</strong> <?php echo htmlspecialchars($info['adresse']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Poids des vêtements:</strong> <?php echo htmlspecialchars($info['poids']); ?> kg</p>
                        <p><strong>Date de collecte souhaitée:</strong> <?php echo htmlspecialchars($info['date']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($info['description']); ?></p>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <h3>Montant estimé</h3>
                <p class="price-tag"><?php echo number_format($info['montant'], 0, ',', ' '); ?> FCFA</p>
                <p class="text-muted">(100 FCFA par kilogramme)</p>
            </div>

            <div class="text-center">
                <p class="mb-4">Nous vous contacterons dans les plus brefs délais pour confirmer la date de collecte.</p>
                <a href="accueil.php" class="btn btn-confirm">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 