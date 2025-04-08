<?php
// config.php : Configuration de la base de données

define('DB_HOST', 'localhost'); // Hôte de la base de données
define('DB_USER', 'root');      // Utilisateur de la base de données
define('DB_PASS', '');          // Mot de passe de la base de données
define('DB_NAME', 'myshop');    // Nom de la base de données

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . 'myshop', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
