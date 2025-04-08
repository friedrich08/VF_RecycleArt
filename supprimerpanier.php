<?php
session_start();

// Check if an item ID was passed in the URL
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    
    // Check if the item exists in the cart
    if (isset($_SESSION['panier'][$id])) {
        // Remove the item from the cart
        unset($_SESSION['panier'][$id]);
        
        // Optionally, you can add a message to indicate success
        $_SESSION['message'] = "Produit supprimé avec succès.";
    } else {
        $_SESSION['message'] = "Produit non trouvé.";
    }
}

// Redirect back to the cart page
header("Location: panier.php");
exit();