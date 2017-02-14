<?php
session_start();
?>

<?php
require_once ('../class/LigneFF.class.php');
require_once ('../class/LigneFHF.class.php');
require_once ('../class/fichefrais.class.php');
require_once ('../includes/dao.inc.php');
require_once ('../class/utilisateur.class.php');
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="../includes/libs.js"></script>
    </head>
    <body>

        <!--en téte de la page--> 
        <?php include ("../includes/headerComptable_CSS.php");?>

       
        <?php
        //Récuperer la date en US.
        $dateUs = date("Y/m/d");

        //ACTION pour récupérer tous les utilisateurs où l'état de la fiche est sur CL
        $ligneFF = new Fichefrais();
        $ligneFiche = $ligneFF->selectFicheCL();

        // Action pour enregister le montant et le nbr de justificatifs de toutes les fiches CL
        foreach ($ligneFiche as $cle => $values) {
            $ligneFF->setIdVisiteur($ligneFiche[$cle]['idVisiteur']);
            $ligneFF->setMois($ligneFiche[$cle]['mois']);
            $ligneFF->setDateModif($dateUs);
            $ligneFF->updateFicheFraisMontant();
        }


        //Action pour modifier idEtat'CL' en VA
        if (isset($_POST['valider'])) {
            //$idEtat = 'VA';

            $updateCL = new Fichefrais();

            //$updateCL->setIdEtat();
            //$updateCL->setMois($_POST['mois']);
            //$updateCL->setIdVisiteur($_POST['idVisiteur']);

            $updateCL->updateFicheFraisVA();
        }

        //ACTION pour récupérer tous les utilisateurs où l'état de la fiche est sur CL
        $ligneFF = new Fichefrais();
        $ligneFiche = $ligneFF->selectFicheCL();
        ?>


        <?php if (!empty($ligneFiche)) { ?>
            <h1>Valider les fiches frais</h1>

            <table border='1'  width='70%'>
                <p>Frais Forfait</p>
                <tr>
                    <th>Date</th>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Prénom</th> 
                    <th>Etat</th> 
                    <th>Total</th> 
                </tr>
                <tr>



                    <?php
                    //récupérer tous les utilisateurs ou l'état de la fiche est sur CL
                    foreach ($ligneFiche as $cle => $values) {
                        ?>
                    <form method="post" action="comptableValider.php">
                        <td><?php echo $ligneFiche[$cle]['mois']; ?> </td>
                        <input name="mois" type="hidden" value="<?php echo $ligneFiche[$cle]['mois']; ?>">
                        <td><?php echo $ligneFiche[$cle]['idVisiteur']; ?> </td>
                        <input name="idVisiteur" type="hidden" value="<?php echo $ligneFiche[$cle]['idVisiteur']; ?>"> 
                        <td><?php echo $ligneFiche[$cle]['nom']; ?> </td>
                        <td><?php echo $ligneFiche[$cle]['prenom']; ?> </td>
                        <td><?php echo $ligneFiche[$cle]['idEtat']; ?> </td>
                        <td><?php echo $ligneFiche[$cle]['montantValide']; ?> </td>

                    <?php } ?>
                    <button id="valider" name="valider" class="" onclick="return modifier3()">Valider</button>

                </form> 
            <?php } ?>



    </body>
</html>
