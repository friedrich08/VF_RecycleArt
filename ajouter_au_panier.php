<?php
session_start();

// Données des produits (exemple)
$products = [
    [
        "id" => 1,
        "name" => "T-shirt Premium Coton",
        "price" => "2000 FCFA",
        "image" => "T-shirt en cotton.jpg",
        "description" => "T-shirt en coton bio de haute qualité, coupe moderne et confortable.",
        "category" => "Casual"
    ],
    [
        "id" => 2,
        "name" => "Jean Slim Fit",
        "price" => "3000 FCFA",
        "image" => "jean.avif",
        "description" => "Jean coupe slim en denim stretch, parfait pour un look décontracté.",
        "category" => "Casual"
    ],
    [
        "id" => 3,
        "name" => "Robe d'Été Florale",
        "price" => "5000 FCFA",
        "image" => "robe ete.jpg",
        "description" => "Robe légère à motifs floraux, idéale pour la saison estivale.",
        "category" => "Été"
    ],
    [
        "id" => 4,
        "name" => "Veste en Cuir",
        "price" => "15000 FCFA",
        "image" => "veste en cuire.avif",
        "description" => "Veste en cuir véritable, style classique et intemporel.",
        "category" => "Vestes"
    ],
    [
        "id" => 5,
        "name" => "T-shirt",
        "price" => "2000 FCFA",
        "image" => "T-shirt.avif",
        "description" => "Pull chaud et confortable en laine mérinos, parfait pour l'hiver.",
        "category" => "Hiver"
    ],
    [
        "id" => 6,
        "name" => "Chemise Business",
        "price" => "4500 FCFA",
        "image" => "chemise.avif",
        "description" => "Chemise élégante en coton, coupe ajustée pour un look professionnel.",
        "category" => "Business"
    ],
    [
        "id" => 7,
        "name" => "Short",
        "price" => "1500 FCFA",
        "image" => "short.jpg",
        "description" => "Short léger et respirant, parfait pour vos activités sportives.",
        "category" => "Sport"
    ],
    [
        "id" => 8,
        "name" => "Blazer Élégant",
        "price" => "20000 FCFA",
        "image" => "blazer.jpg",
        "description" => "Blazer moderne, parfait pour les occasions formelles.",
        "category" => "Business"
    ],
    [
        "id" => 9,
        "name" => "Robe de Soirée",
        "price" => "10000 FCFA",
        "image" => "robe de soirée.jpg",
        "description" => "Robe élégante pour vos soirées spéciales.",
        "category" => "Soirée"
    ],
    [
        "id" => 10,
        "name" => "Pantalon Chino",
        "price" => "3000 FCFA",
        "image" => "pantalon.avif",
        "description" => "Pantalon chino en coton, coupe droite et confortable.",
        "category" => "Casual"
    ],
    [
        "id" => 11,
        "name" => "Robe Cocktail",
        "price" => "6000 FCFA",
        "image" => "robe cocktail.avif",
        "description" => "Robe courte élégante pour vos cocktails et événements.",
        "category" => "Soirée"
    ],
    [
        "id" => 12,
        "name" => "Veste de Sport",
        "price" => "3500 FCFA",
        "image" => "veste de sport.jpg",
        "description" => "Veste technique respirante pour vos activités sportives.",
        "category" => "Sport"
    ]

];

// Vérifiez si le panier existe
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Vérifiez si l'ID du produit est défini et est un entier
if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']); // Convertir en entier pour éviter les injections

    // Trouver le produit correspondant
    $found_product = null;
    foreach ($products as $product) {
        if ($product['id'] === $product_id) {
            $found_product = $product;
            break;
        }
    }

    // Si le produit est trouvé, ajoutez-le au panier
    if ($found_product) {
        $product_name = $found_product['name'];
        $product_price = $found_product['price'];

        // Ajoutez le produit au panier
        $found = false;
        foreach ($_SESSION['panier'] as &$item) {
            if ($item['id'] === $product_id) {
                $item['quantity'] += 1; // Augmenter la quantité si déjà dans le panier
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['panier'][] = [
                'id' => $product_id,
                'name' => $product_name,
                'price' => $product_price,
                'quantity' => 1 // Initialiser la quantité à 1
            ];
        }

        // Optionnel : Message de succès
        $_SESSION['message'] = "Le produit {$product_name} a été ajouté au panier.";
    } else {
        // Produit non trouvé, gérez cela
        $_SESSION['error'] = "Produit non trouvé.";
    }
} else {
    // ID de produit non défini ou invalide
    $_SESSION['error'] = "ID de produit invalide.";
}

// Redirigez vers la page du panier
header('Location: accueil.php');
exit();
?>