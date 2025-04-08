<?php
session_start();

$message = isset($_SESSION['payment_message']) ? $_SESSION['payment_message'] : '';
$panier = $_SESSION['panier'] ?? [];
$total = 0;
$modeDePaiement = isset($_SESSION['mode_de_paiement']) ? $_SESSION['mode_de_paiement'] : 'Non spécifié';
unset($_SESSION['payment_message']);

// Calculer le total
foreach ($panier as $item) {
    $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
    $total += (float)$item['price'] * $quantity;
}

// Générer le fichier CSV
if (isset($_POST['generate_csv'])) {
    $filename = 'ticket_paiement_' . date('Y-m-d_H-i-s') . '.csv';
    
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // En-tête du document
    fputcsv($output, ['TICKET DE PAIEMENT - RECYCLEART']);
    fputcsv($output, ['Date: ' . date('d/m/Y H:i:s')]);
    fputcsv($output, ['Mode de Paiement: ' . $modeDePaiement]);
    fputcsv($output, ['']); // Ligne vide
    
    // En-tête du tableau
    fputcsv($output, ['Produit', 'Prix unitaire', 'Quantité', 'Total']);
    fputcsv($output, ['']); // Ligne vide
    
    // Détails des produits
    foreach ($panier as $item) {
        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
        $itemTotal = (float)$item['price'] * $quantity;
        fputcsv($output, [
            htmlspecialchars($item['name']),
            htmlspecialchars($item['price']) . ' FCFA',
            $quantity,
            $itemTotal . ' FCFA'
        ]);
    }
    
    // Ligne de séparation
    fputcsv($output, ['']); // Ligne vide
    
    // Total
    fputcsv($output, ['', '', 'Total:', $total . ' FCFA']);
    
    // Pied de page
    fputcsv($output, ['']);
    fputcsv($output, ['Merci de votre achat chez RecycleArt']);
    fputcsv($output, ['www.recycleart.com']);
    
    fclose($output);
    exit();
}

// Générer le fichier PDF
if (isset($_POST['generate_pdf'])) {
    require_once('vendor/autoload.php');

    // Créer un nouveau document PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Définir les informations du document
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('RecycleArt');
    $pdf->SetTitle('Ticket de Paiement');

    // Supprimer l'en-tête et le pied de page par défaut
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Définir les marges
    $pdf->SetMargins(15, 15, 15);

    // Ajouter une page
    $pdf->AddPage();

    // Logo
    $pdf->Image('logo.jpg', 15, 15, 30);

    // Titre
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'TICKET DE PAIEMENT', 0, 1, 'C');
    $pdf->Ln(5);

    // Date
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Date: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
    $pdf->Ln(5);

    // Informations de paiement
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Informations de Paiement', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Mode de Paiement: ' . $modeDePaiement, 0, 1, 'L');
    $pdf->Cell(0, 10, 'Statut: ' . $message, 0, 1, 'L');
    $pdf->Ln(5);

    // En-tête du tableau
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(80, 10, 'Produit', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Prix unitaire', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Quantité', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Total', 1, 1, 'C');

    // Détails des produits
    $pdf->SetFont('helvetica', '', 12);
    foreach ($panier as $item) {
        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
        $itemTotal = (float)$item['price'] * $quantity;
        
        $pdf->Cell(80, 10, $item['name'], 1, 0, 'L');
        $pdf->Cell(30, 10, $item['price'] . ' FCFA', 1, 0, 'C');
        $pdf->Cell(30, 10, $quantity, 1, 0, 'C');
        $pdf->Cell(30, 10, $itemTotal . ' FCFA', 1, 1, 'C');
    }

    // Total
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(140, 10, 'Total', 1, 0, 'R');
    $pdf->Cell(30, 10, $total . ' FCFA', 1, 1, 'C');

    // Pied de page
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Cell(0, 10, 'Merci de votre achat chez RecycleArt', 0, 1, 'C');
    $pdf->Cell(0, 10, 'www.recycleart.com', 0, 1, 'C');

    // Générer le PDF
    $pdf->Output('ticket_paiement_' . date('Y-m-d_H-i-s') . '.pdf', 'D');
    exit();
}

// Si on arrive ici, c'est qu'on n'a pas demandé de génération de fichier
// On peut donc afficher la page HTML normalement
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du Paiement - RecycleArt</title>
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
        .result-card {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .table {
            margin-top: 2rem;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .btn-download {
            background-color: #28a745;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }
        .btn-download:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }
        .payment-message {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        <div class="result-card">
            <h1 class="text-center mb-4">Résultat du Paiement</h1>
            
            <div class="payment-message <?php echo strpos($message, 'succès') !== false ? 'success' : 'error'; ?>">
                <p class="mb-0"><?php echo htmlspecialchars($message); ?></p>
            </div>

            <?php if (!empty($panier)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($panier as $id => $item): 
                                $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                                $itemTotal = (float)$item['price'] * $quantity;
                                $total += $itemTotal;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['price']); ?> FCFA</td>
                                    <td><?php echo htmlspecialchars($quantity); ?></td>
                                    <td><?php echo htmlspecialchars($itemTotal); ?> FCFA</td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-active">
                                <td colspan="3"><strong>Total</strong></td>
                                <td><strong><?php echo htmlspecialchars($total); ?> FCFA</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <form method="POST" class="d-inline">
                        <button type="submit" name="generate_csv" class="btn btn-download">
                            <i class="fas fa-file-csv me-2"></i>Télécharger en CSV
                        </button>
                        <button type="submit" name="generate_pdf" class="btn btn-download">
                            <i class="fas fa-file-pdf me-2"></i>Télécharger en PDF
                        </button>
                    </form>
                    <a href="accueil.php" class="btn btn-back">
                        <i class="fas fa-home me-2"></i>Retour à l'accueil
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>Aucun article n'a été payé.
                </div>
                <div class="text-center mt-4">
                    <a href="accueil.php" class="btn btn-back">
                        <i class="fas fa-home me-2"></i>Retour à l'accueil
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>