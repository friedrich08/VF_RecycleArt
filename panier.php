<?php
session_start();

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: connexion.php");
    exit();
}

// Vérifiez si un produit doit être supprimé
if (isset($_GET['remove'])) {
    $idToRemove = $_GET['remove'];
    if (isset($_SESSION['panier'][$idToRemove])) {
        unset($_SESSION['panier'][$idToRemove]);
    }
}

// Vérifiez si le formulaire a été soumis pour mettre à jour les quantités
if (isset($_POST['update_quantity'])) {
    foreach ($_POST['quantity'] as $id => $quantity) {
        if (is_numeric($quantity) && $quantity > 0) {
            $_SESSION['panier'][$id]['quantity'] = (int)$quantity;
        } else {
            unset($_SESSION['panier'][$id]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - RecycleArt</title>
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="accueil.php">
                <img src="logo.jpg" alt="RecycleArt" height="80" class="d-inline-block align-top" style="margin-top: -10px; margin-bottom: -10px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="accueil.php">
                            <i class="fas fa-home me-1"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="connexion.php" class="nav-link btn btn-warning">
                            <i class="fas fa-user-plus me-1"></i>S'inscrire
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning position-relative active" href="panier.php">
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

    <div class="container main-content">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title mb-4">Mon Panier</h1>
                        
                        <?php if (isset($_SESSION['client'])): ?>
                            <p class="text-success mb-4">
                                <i class="fas fa-user me-2"></i>
                                Bienvenue, <?php echo htmlspecialchars($_SESSION['client']); ?>!
                            </p>
                        <?php else: ?>
                            <p class="text-muted mb-4">
                                <i class="fas fa-user me-2"></i>
                                Bienvenue, invité!
                            </p>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
                            <form method="POST">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Produit</th>
                                                <th>Prix</th>
                                                <th>Quantité</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total = 0;
                                            foreach ($_SESSION['panier'] as $id => $item): 
                                                $price = isset($item['price']) ? (float)$item['price'] : 0;
                                                $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                                                $itemTotal = $price * $quantity;
                                                $total += $itemTotal;
                                            ?>
                                                <tr class="align-middle">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php if (isset($item['image'])): ?>
                                                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                            <?php endif; ?>
                                                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td><?php echo number_format($price, 0, ',', ' '); ?> FCFA</td>
                                                    <td>
                                                        <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo htmlspecialchars($quantity); ?>" min="1" class="form-control" style="width: 100px;">
                                                    </td>
                                                    <td><?php echo number_format($itemTotal, 0, ',', ' '); ?> FCFA</td>
                                                    <td>
                                                        <a href="panier.php?remove=<?php echo $id; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-light fw-bold">
                                                <td colspan="3" class="text-end">Total</td>
                                                <td><?php echo number_format($total, 0, ',', ' '); ?> FCFA</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="submit" name="update_quantity" class="btn btn-outline-primary">
                                        <i class="fas fa-sync-alt me-2"></i>
                                        Mettre à jour
                                    </button>
                                    <a href="payement.php" class="btn btn-success">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Procéder au paiement
                                    </a>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="lead mb-4">Votre panier est vide.</p>
                                <a href="accueil.php" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag me-2"></i>
                                    Continuer mes achats
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>