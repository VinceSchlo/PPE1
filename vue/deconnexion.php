<?php
// Deconnexion de l'utilisateur 
//on détruit la session puis redirige vers la page d'acceuil
session_start();
session_destroy();
header("Location:index1.php");
?>
