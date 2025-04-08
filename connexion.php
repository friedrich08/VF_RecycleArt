<?php
session_start();  // Démarrer la session pour gérer l'authentification
require 'bdd.php';
// Créer une instance de la classe Database
$database = new Database();
$conn = $database->getConnection(); // Obtenir la connexion

//if ($conn) {
   // echo "Connected successfully";
//} else {
   // echo "Connection failed";
//}

// Messages d'erreur/succès
$message = '';
$messageType = '';

// Partie Inscription
if (isset($_POST['register'])) {
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password)) {
        // Validation du mot de passe
        if (strlen($password) < 8) {
            $message = "Le mot de passe doit contenir au moins 8 caractères.";
            $messageType = "danger";
        } else {
            // Vérification si l'email existe déjà
            $stmt = $conn->prepare("SELECT * FROM client WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $message = "Cet email est déjà utilisé.";
                $messageType = "danger";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("INSERT INTO client (nom, prenom, email, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nom, $prenom, $email, $hashedPassword);
                
                if ($stmt->execute()) {
                    $_SESSION['client'] = $email;
                    $_SESSION['logged_in'] = true;
                    header("Location: accueil.php");
                    exit();
                } else {
                    $message = "Erreur lors de l'inscription.";
                    $messageType = "danger";
                }
            }
        }
    } else {
        $message = "Tous les champs sont requis.";
        $messageType = "danger";
    }
}

// Partie Connexion
if (isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM client WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['client'] = $email;
                $_SESSION['logged_in'] = true;
                header("Location: accueil.php");
                exit();
            } else {
                $message = "Mot de passe incorrect.";
                $messageType = "danger";
            }
        } else {
            $message = "Aucun utilisateur trouvé avec cet email.";
            $messageType = "danger";
        }
    } else {
        $message = "Tous les champs sont requis.";
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - RecycleArt</title>
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
                        <a class="nav-link" href="accueil.php#about">
                            <i class="fas fa-info-circle me-1"></i>À propos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="accueil.php#products">
                            <i class="fas fa-shopping-bag me-1"></i>Articles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="accueil.php#contact">
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

    <div class="container main-content">
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?> fade-in">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-4" id="authTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button">Connexion</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button">Inscription</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="authTabsContent">
                            <!-- Formulaire de connexion -->
                            <div class="tab-pane fade show active" id="login" role="tabpanel">
                                <form method="POST" class="fade-in">
                                    <div class="form-group mb-3">
                                        <label for="login-email">Email</label>
                                        <input type="email" class="form-control" id="login-email" name="email" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="login-password">Mot de passe</label>
                                        <input type="password" class="form-control" id="login-password" name="password" required>
                                    </div>
                                    <button type="submit" name="login" class="btn btn-success w-100">Se connecter</button>
                                </form>
                            </div>

                            <!-- Formulaire d'inscription -->
                            <div class="tab-pane fade" id="register" role="tabpanel">
                                <form method="POST" class="fade-in">
                                    <div class="form-group mb-3">
                                        <label for="register-nom">Nom</label>
                                        <input type="text" class="form-control" id="register-nom" name="nom" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="register-prenom">Prénom</label>
                                        <input type="text" class="form-control" id="register-prenom" name="prenom" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="register-email">Email</label>
                                        <input type="email" class="form-control" id="register-email" name="email" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="register-password">Mot de passe</label>
                                        <input type="password" class="form-control" id="register-password" name="password" required>
                                        <small class="form-text text-muted">Le mot de passe doit contenir au moins 8 caractères.</small>
                                    </div>
                                    <button type="submit" name="register" class="btn btn-success w-100">S'inscrire</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des formulaires
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.fade-in').forEach(form => {
                    form.style.opacity = 0;
                    setTimeout(() => {
                        form.style.opacity = 1;
                    }, 100);
                });
            });
        });
    </script>
</body>
</html>