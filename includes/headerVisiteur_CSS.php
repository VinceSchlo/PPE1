<!DOCTYPE html>
<html lang="fr">
<head>
    <title></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/test.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/headerComptable.css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<!--<link rel="stylesheet" href="../css/headerVisiteur.css" />-->

<header>

    <div class="container-fluid police-white header-padding">
<div class="row top-background ">

        <div class="col-xs-1 center-top"> <img class="logo" src="../images/logoo.png"></div>
        <div class="col-xs-2"> <?php echo "Bonjour,  " . $_SESSION['nom'] . "  " . $_SESSION['prenom'] . "<br />"; ?></div>
        <div class="col-xs-offset-6 col-xs-2">Statut : Consultant</div>
        <div class="col-xs-1"> <a class="Deconnexion" href="../vue/deconnexion.php">Deconnexion</a></div>

 <ul class="nav ">
     <div class="col-xs-offset-1 col-xs-1"><li><a class="lien" href="formFF.php">Frais au forfait</a></li></div>
     <div class="col-xs-2"><li><a class="lien" href="formFHF.php">Frais hors forfait</a></li></div>
     <div class="col-xs-1"><li ><a class="lien" href="consultation.php">Consultation</a></li></div>
     <div class="col-xs-1"><li ><a class="lien" href="modifProfile.php">Compte</a></li></div>
 </ul>


</div>
</div>

</header>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>

</html>