<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendre vos vêtements - RecycleArt</title>
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
        .sell-form {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .price-info {
            background-color: #e8f5e9;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
            text-align: center;
        }
        .price-tag {
            font-size: 1.5rem;
            color: #2e7d32;
            font-weight: bold;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .info-section {
            margin-top: 3rem;
            padding: 2rem;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .info-icon {
            font-size: 2rem;
            color: #28a745;
            margin-bottom: 1rem;
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
        <div class="sell-form">
            <h1 class="text-center mb-4">Vendez vos vêtements usagés</h1>
            
            <div class="price-info">
                <h2 class="mb-3">Nous achetons vos vêtements usagés</h2>
                <p class="price-tag">100 FCFA par kilogramme</p>
                <p class="text-muted">Prix fixe pour tous les types de vêtements</p>
            </div>

            <form action="process_sell.php" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Numéro de téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse de collecte</label>
                    <textarea class="form-control" id="adresse" name="adresse" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="poids" class="form-label">Poids estimé des vêtements (en kg)</label>
                    <input type="number" class="form-control" id="poids" name="poids" min="1" step="0.1" required>
                    <div class="form-text">Le poids minimum est de 1 kg</div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description des vêtements</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    <div class="form-text">Décrivez brièvement les types de vêtements que vous souhaitez vendre</div>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Date souhaitée pour la collecte</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer la demande
                    </button>
                </div>
            </form>

            <div class="info-section">
                <h3 class="text-center mb-4">Comment ça marche ?</h3>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="fas fa-box info-icon"></i>
                        <h4>1. Préparez vos vêtements</h4>
                        <p>Rassemblez vos vêtements usagés et pesez-les</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-truck info-icon"></i>
                        <h4>2. Nous venons les chercher</h4>
                        <p>Nous passons à votre adresse pour collecter les vêtements</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-money-bill-wave info-icon"></i>
                        <h4>3. Vous êtes payé</h4>
                        <p>Vous recevez votre paiement immédiatement après la pesée</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Définir la date minimale pour la date de collecte (aujourd'hui)
        document.getElementById('date').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>