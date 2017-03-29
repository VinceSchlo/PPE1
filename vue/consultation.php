<?php
session_start();
?>

<?php
require_once('../class/LigneFF.class.php');
require_once('../class/LigneFHF.class.php');
require_once('../class/fichefrais.class.php');
require_once('../includes/dao.inc.php');
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/html">
<head>
    <title></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/test.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="../includes/libs.js"></script>
</head>
<body>

<?php include("../includes/headerVisiteur_CSS.php"); ?>



<?php
$maValeur = null;
if (isset($_POST['consultation'])) {


    $maValeur = $_POST["consultation"];
}


$fichemois = new Fichefrais();

$fichemois->setIdVisiteur($_SESSION['id']);

$tlmois = $fichemois->selectMoisFiche();
?>

<!-- Séléction du mois -->
<div class="container-fluid">
    <div class="row">
    <form action="consultation.php" method="POST">
        <select class='col-md-offset-4 col-lg-1 form-control' id="consultation" name="consultation">
            <option value="typemois" selected="">Selectionner un mois</option>
            <?php foreach ($tlmois as $cle => $valeur) { ?>

                <option value="<?php echo $tlmois[$cle]['mois']; ?>"
                    <?php if ($maValeur == $tlmois[$cle]['mois']) { ?> selected<?php } ?> >  <?php echo $tlmois[$cle]['mois']; ?>  </option>

            <?php } ?>

        </select>

        <button type="submit" name="afficher" value="afficher">Afficher</button>

        <?php
        // Si l'utilisateur selectionne "Afficher"
        if (isset($_POST['afficher'])) {
        if (stristr($_POST['consultation'], 'typemois') === FALSE) {
        // Création de l'objet $ligneFF de la classe LigneFF
        $ligneFF = new LigneFF();
        $id = $_SESSION['id'];

        $ligneFF->setIdVisiteur($id);
        $ligneFF->setMois($_POST['consultation']);
        // Selection de tous les Frais Forfait
        $listeFF = $ligneFF->selectLffConsultation();


        // Création de l'objet $ligneFHF de la classe LigneFHF
        $ligneFHF = new ligneFHF();

        $ligneFHF->setIdVisiteur($id);
        $ligneFHF->setMois($_POST['consultation']);
        // Selection de tous les Frais Hors Forfait
        $listeFHF = $ligneFHF->selectFhfConsultation();


        // Création d'un objet pour récuperer tout les calculs de frais
        $totallff = new Fichefrais();

        $totallff->setIdVisiteur($id);
        $totallff->setMois($_POST['consultation']);

        $tlf = $totallff->selectTotalFrais();
        $lff = $totallff->selectTotalFF();
        $lfhf = $totallff->selectTotalFHF();
        ?>
    </form>
</div>
    <?php if (!empty($listeFF)) { ?>
        <!-- Affichage des Frais au Forfait pour le mois séléctionné -->
        <table border='1' width='70%'>
            <p>Frais Forfait</p>
            <tr>
                <th>Mois</th>
                <th>Type Frais</th>
                <th>Quantité</th>
            </tr>
            <tr>
                <?php foreach ($listeFF as $cle => $ligneFF) { ?>
                <form method="post" action="formFF.php">
                    <td>  <?php echo $listeFF[$cle]['mois']; ?> </td>
                    <td> <?php echo $listeFF[$cle]['idFraisForfait']; ?> </td>
                    <td> <?php echo $listeFF[$cle]['quantite']; ?> </td>
            </tr>
            <?php } ?>
        </table>
    <?php } ?>
    <br/>

    <?php if (!empty($listeFHF)) { ?>
        <!-- Affichage des Frais HORS Forfait pour le mois séléctionné -->
        <table border='1' width='70%'>
            <p>Frais Hors Forfait</p>
            <tr>
                <th>Mois</th>
                <th>Libelle</th>
                <th>Date Facture</th>
                <th>Montant</th>
            </tr>
            <tr>
                <?php foreach ($listeFHF as $cle => $ligneFHF) { ?>
                <form method="post" action="formFHF.php">
                    <td> <?php echo $listeFHF[$cle]['mois']; ?> </td>
                    <td> <?php echo $listeFHF[$cle]['libelleFrais']; ?> </td>
                    <td> <?php echo $listeFHF[$cle]['date']; ?> </td>
                    <td> <?php echo $listeFHF[$cle]['montantFrais']; ?> </td>
            </tr>
            <?php } ?>
        </table>
    <?php } ?>
    <?php

    echo "ETAT : " . $tlf['idEtat'] . "<br/>";
    echo "Nbr Justificatifs : " . $tlf['nbJustificatifs'] . "<br/>";
    echo "Total Frais au Forfait : " . $lff['totalfraisforfait'] . "<br/>";
    echo "Total Frais Hors Forfait : " . $lfhf['total'] . "<br/>";
    echo "Total : " . $tlf['montantValide'];
    }
    }
    ?>
</div>

</body>
</html>
