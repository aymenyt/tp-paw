<?php
// Affiche toutes les erreurs pour le debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion
require 'db_connect.php';

// Tenter la connexion
$conn = getConnection();

// Vérifier le résultat
if ($conn) {
    echo "<p style='color:green'>Connection successful</p>";
} else {
    echo "<p style='color:red'>Connection failed</p>";
}
