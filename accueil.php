<?php
session_start();

// Traitement du formulaire de contact
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contact_submit'])) {
    // Récupérer les données du formulaire
    $name = htmlspecialchars($_POST['name']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $message = htmlspecialchars($_POST['message']);

    // Formater le message pour WhatsApp
    $formattedMessage = urlencode("Nom: $name $prenom\nMessage: $message");

    // Rediriger vers WhatsApp avec le message formaté
    $whatsappUrl = "https://wa.me/22899337233?text=$formattedMessage";
    header("Location: $whatsappUrl");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - RecycleArt</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="accueil.css">
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
                            <i class="fas fa-hand-holding-heart me-1"></i>Je vends
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

    <!-- Hero Section -->
    <div class="hero-section position-relative">
        <img src="Acceuil.jpg" class="hero-image w-100" alt="Shopping" style="height: 600px; object-fit: cover;">
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
            <h1 class="display-4 fw-bold mb-4">Nos Nouveaux Produits</h1>
            <p class="lead mb-4">Découvrez les dernières tendances de RecycleArt !</p>
            <a href="#products" class="btn btn-warning btn-lg">Découvrir la collection</a>
        </div>
    </div>

    <!-- About Section -->
    <section id="about" class="about-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">À propos de RecycleArt</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <p class="text-center">
                        Bienvenue chez RecycleArt, votre destination en ligne pour une mode tendance et accessible. Découvrez notre sélection soigneusement choisie de vêtements pour hommes et femmes, alliant style, qualité et confort. Que vous recherchiez des pièces casual, des tenues élégantes ou des accessoires pour parfaire votre look, nous avons ce qu'il vous faut. Profitez d'une expérience d'achat simple et rapide, avec des livraisons fiables et un service client dédié. RecycleArt – où la mode rencontre la praticité !
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products-section py-5">
        <div class="container">
            <h2 class="text-center mb-4">Nos Articles</h2>
            
            <!-- Search bar with integrated filter -->
            <div class="search-bar mb-4">
                <form method="GET" action="" class="row g-3 justify-content-center">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher un produit..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="all" <?= (!isset($_GET['category']) || $_GET['category'] === 'all') ? 'selected' : '' ?>>Toutes les catégories</option>
                            <?php
                            $categories = [
                                ['nom' => 'Materiel de construction'],
                                ['nom' => 'Meuble'],
                                ['nom' => 'Objet de decoration'],
                            ];
                            foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['nom']) ?>" <?= (isset($_GET['category']) && $_GET['category'] === $category['nom']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                    </div>
                </form>
            </div>

            <!-- Products grid -->
            <div class="row g-4">
                <?php
                // Données des produits
                $products = [
                    [
                        "id" => 1, 
                        "name" => "Panneau mural en fibres recyclées", 
                        "price" => "2000 FCFA",
                        "image" => "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", 
                        "description" => "Panneau écologique fabriqué à partir de déchets textiles compressés, idéal pour l'isolation ou la décoration intérieure.",
                        "category" => "Materiel de construction"
                    ],
                    [
                        "id" => 2, 
                        "name" => "Brique textile compressée", 
                        "price" => "3000 FCFA",
                        "image" => "data:image/jpeg;base64,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", 
                "description" => "Brique légère et résistante faite de textiles recyclés, parfaite pour des cloisons éco-responsables.",
                "category" => "Materiel de construction"
            ],
            [
                "id" => 3, 
                "name" => "Dalle de sol en tissu recyclé", 
                "price" => "5000 FCFA",
                "image" => "https://images.pexels.com/photos/3932930/pexels-photo-3932930.jpeg?auto=compress&cs=tinysrgb&w=800", 
                "description" => "Dalle durable conçue à partir de fibres textiles usagées, pour un sol esthétique et écologique.",
                "category" => "Materiel de construction"
            ],
            [
                "id" => 4, 
                "name" => "Isolant thermique textile", 
                "price" => "15000 FCFA",
                "image" => "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", 
                "description" => "Matériau d'isolation haute performance fabriqué à partir de vêtements recyclés, pour une maison éco-énergétique.",
                "category" => "Materiel de construction"
            ],
            [
                "id" => 5, 
                "name" => "Tabouret en denim recyclé", 
                "price" => "2000 FCFA",
                "image" => "https://images.pexels.com/photos/1350789/pexels-photo-1350789.jpeg?auto=compress&cs=tinysrgb&w=800", 
                "description" => "Tabouret robuste et stylé, rembourré avec des chutes de jeans recyclés, parfait pour un intérieur durable.",
                "category" => "Meuble"
            ],
            [
                "id" => 6, 
                "name" => "Étagère en tissu tressé", 
                "price" => "4500 FCFA",
                "image" => "https://images.pexels.com/photos/1090638/pexels-photo-1090638.jpeg?auto=compress&cs=tinysrgb&w=800", 
                "description" => "Étagère artisanale fabriquée avec des cordes de textiles recyclés, pour un rangement éco-chic.",
                "category" => "Meuble"
            ],
            [
                "id" => 7, 
                "name" => "Chaise en fibres textiles", 
                "price" => "1500 FCFA",
                "image" => "https://images.pexels.com/photos/1148955/pexels-photo-1148955.jpeg?auto=compress&cs=tinysrgb&w=800", 
                "description" => "Chaise légère et résistante, conçue à partir de fibres de vêtements usagés, pour une assise écologique.",
                "category" => "Meuble"
            ],
            [
                "id" => 8, 
                "name" => "Tapis mural en chutes de tissu", 
                "price" => "20000 FCFA",
                "image" => "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", 
                "description" => "Tapis décoratif mural tissé à la main avec des restes de tissus recyclés, pour une touche bohème durable.",
                "category" => "Objet de decoration"
            ],
            [
                "id" => 9, 
                "name" => "Suspension lumineuse textile", 
                "price" => "10000 FCFA",
                "image" => "https://images.pexels.com/photos/1125132/pexels-photo-1125132.jpeg?auto=compress&cs=tinysrgb&w=800", 
                "description" => "Lampe suspendue élégante fabriquée à partir de bandes de textiles recyclés, pour un éclairage éco-friendly.",
                "category" => "Objet de decoration"
            ],
            [
                "id" => 10, 
                "name" => "Coussin en patchwork recyclé", 
                "price" => "3000 FCFA",
                "image" => "https://images.pexels.com/photos/1350789/pexels-photo-1350789.jpeg?auto=compress&cs=tinysrgb&w=800", 
                "description" => "Coussin confortable et unique, créé avec des chutes de tissus recyclés en patchwork coloré.",
                "category" => "Objet de decoration"
            ],
            [
                "id" => 11, 
                "name" => "Table basse en textile compressé", 
                "price" => "6000 FCFA",
                "image" => "https://images.pexels.com/photos/1866149/pexels-photo-1866149.jpeg?auto=compress&cs=tinysrgb&w=800", 
                "description" => "Table basse originale fabriquée avec des déchets textiles compressés, pour un salon éco-responsable.",
                "category" => "Meuble"
            ],
            [
                "id" => 12, 
                "name" => "Poutre décorative en tissu", 
                "price" => "3500 FCFA",
                "image" => "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", 
                "description" => "Poutre légère en fibres textiles recyclées, pour une touche rustique et écologique dans votre intérieur.",
                "category" => "Materiel de construction"
            ]
        ];

        // Récupérer la catégorie sélectionnée (ou 'all' par défaut)
        $selectedCategory = $_GET['category'] ?? 'all';

        // Récupérer la recherche
        $searchQuery = isset($_GET['search']) ? strtolower($_GET['search']) : '';

        // Filtrer les produits
        foreach ($products as $product) {
            $matchesCategory = ($selectedCategory === 'all' || $product['category'] === $selectedCategory);
            $matchesSearch = (empty($searchQuery) || 
                strpos(strtolower($product['name']), $searchQuery) !== false || 
                strpos(strtolower($product['description']), $searchQuery) !== false);

            if ($matchesCategory && $matchesSearch) {
                echo '
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 product-card">
                        <img src="' . htmlspecialchars($product["image"]) . '" class="card-img-top" alt="' . htmlspecialchars($product["name"]) . '" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($product["name"]) . '</h5>
                            <p class="card-text text-primary fw-bold">' . htmlspecialchars($product["price"]) . '</p>
                            <p class="card-text small">' . htmlspecialchars($product["description"]) . '</p>
                            <form method="POST" action="ajouter_au_panier.php" class="mt-3">
                                <input type="hidden" name="product_id" value="' . intval($product['id']) . '">
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                </button>
                            </form>
                        </div>
                    </div>
                </div>';
            }
        }
        ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Contactez-nous</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form class="contact-form" action="" method="post">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Votre nom" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="prenom" class="form-control" placeholder="Votre prénom" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control" placeholder="Votre message" rows="5" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="contact_submit" class="btn btn-primary btn-lg">
                                <i class="fab fa-whatsapp me-2"></i>Envoyer via WhatsApp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 RecycleArt. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>