<?php
// Créer le dossier vendor s'il n'existe pas
if (!file_exists('vendor')) {
    mkdir('vendor', 0777, true);
}

// Télécharger TCPDF
$tcpdfUrl = 'https://github.com/tecnickcom/TCPDF/archive/refs/tags/6.6.2.zip';
$zipFile = 'vendor/tcpdf.zip';
$extractPath = 'vendor/';

// Télécharger le fichier ZIP
file_put_contents($zipFile, file_get_contents($tcpdfUrl));

// Extraire le fichier ZIP
$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    $zip->extractTo($extractPath);
    $zip->close();
    unlink($zipFile);
    echo "TCPDF a été installé avec succès!";
} else {
    echo "Erreur lors de l'installation de TCPDF";
}
?> 