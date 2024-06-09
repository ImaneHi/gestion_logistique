<?php
// Configuration de la base de données
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "logistique"; 


// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}
?>
