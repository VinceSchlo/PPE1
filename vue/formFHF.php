<?php
session_start();
?>
<?php
require_once('../class/LigneFHF.class.php');
require_once('../includes/dao.inc.php');
require_once('../class/fichefrais.class.php');
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Frais hors forfait</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/test.css" rel="stylesheet">
    <!--        <link href="../bootstrap/css/test.css" rel="stylesheet">-->
    <!-- HTML5 Shim and Respond.js IE²&
    c fd=8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="../includes/libs.js"></script>
</head>

<body class="">

<?php include("../includes/headerVisiteur_CSS.php"); ?>

<?php
$date = date("d-m-Y");
$mois = substr($date, 6) . substr($date, 3, 2);

//Récuperer la date en US.
$dateUs = date("Y/m/d");


// Action pour Ajouter un Frais Hors Forfait
if (isset($_POST['ajouter'])) {
    //Vérification si l'utilisateur rentre une donnée numérique
    if (is_numeric($_POST['montant'])) {


        $fichefrais = new Fichefrais();

        $idEtat = "CR";

        $fichefrais->setIdVisiteur($_SESSION['id']);
        $fichefrais->setMois($mois);
        $fichefrais->setIdEtat($idEtat);
        $fichefrais->setDateModif($dateUs);

        $fichefrais->verifierFicheFrais();


        $ligneFHF = new LigneFHF();

        $id = $_SESSION['id'];

        $libelle = $_POST['libelle'];

        $montant = $_POST['montant'];

        $ligneFHF->setIdVisiteur($id);
        $ligneFHF->setMois($mois);
        $ligneFHF->setLibelleFrais($libelle);
        $ligneFHF->setMontantFrais($montant);
        $ligneFHF->setDate($_POST['datejustif']);

        $resu = $ligneFHF->ajoutFHF();
    } else {
        ?>
        <script>alert('Veuiller rentrer une valeur numérique')</script> <?php
    }
}

// Action pour Modifier un Frais Hors Forfait.
if (isset($_POST['modifier'])) {

    if (is_numeric($_POST['montant'])) {

        $idFHF = $_POST['id'];
        $libelle = $_POST['libelle'];
        $montant = $_POST['montant'];

        $ligneFHF = new LigneFHF();

        $ligneFHF->setId($idFHF);
        $ligneFHF->setLibelleFrais($libelle);
        $ligneFHF->setMontantFrais($montant);
        $ligneFHF->setMois($mois);
        $ligneFHF->setDate($_POST['datejustif']);

        $ligneFHF->updateLigneFHF();
        ?>
        <script>alert('La mofification à bien été prie en compte')</script> <?php
    } else {
    ?>
        <script>alert('Veuiller rentrer une valeur numérique')</script> <?php
    }
}

// Action pour Supprimer un Frais Hors Forfait.
if (isset($_POST['supprimer'])) {

    $ligneFHF = new LigneFHF();
    $libelle = $_POST['libelle'];
    $idFHF = $_POST['id'];


    $ligneFHF->setLibelleFrais($libelle);
    $ligneFHF->setIdVisiteur($_SESSION['id']);
    $ligneFHF->setMois($mois);
    $ligneFHF->setId($idFHF);

    $ligneFHF->deleteLigneFHF();
}

// Action pour Séléctionner tout les Frais Hors Forfait du mois en cours.
$ligneFHF = new ligneFHF();
$id = $_SESSION['id'];

$ligneFHF->setIdVisiteur($id);
$ligneFHF->setMois($mois);

$listeFHF = $ligneFHF->tousLesLfhf();
?>
<div class="container-fluid">
    <div class="row">
        <section class="col-xs-offset-1 col-xs-4">

            <h1 class="title">Frais hors forfait</h1>
            <img class="barre" src="../images/barre.PNG">

            <p>Nom : <?php echo $_SESSION['nom']; ?></p>
            <p>Prénom : <?php echo $_SESSION['prenom']; ?></p>


            <!-- Formulaire d'ajout d'un nouveau Frais Hors Forfait -->
            <form action="formFHF.php" method="post">

                <div>
                    <h1 class="title"> Période d'engagement </h1>
                    <img class="barre" src="../images/barre.PNG">

                    <?php
                    $moisaffich = date("m");
                    $anneeaffich = date("Y");
                    ?>
                    <p>mois : <?php echo $moisaffich; ?> </p>
                    <p>année : <?php echo $anneeaffich; ?></p>
                </div>

        </section>
        <section class="col-xs-4">
            <h1 class="title">Ajout frais hors forfait </h1>
            <img class="barre" src="../images/barre.PNG">
            <div>
                <label for="libelle">Libelle</label><br/>
                <input class="form-control center" type="text" id="libelle" name="libelle"
                       placeholder="25 caractéres maximun" value="" required
                       maxlength="25"/>
            </div>

            <div>
                <label for="datejustif">Date du justificatif</label><br/>
                <input class="form-control center" type="date" id="datejustif" name="datejustif" value=""/>
            </div>

            <div>
                <label for="montant">Montant</label><br/>
                <input class="form-control center" type="text" id="montant" name="montant" placeholder="" value=""
                       required/>
            </div>

            <div class="button">
                <button class="btn btn-success" type="submit" name="ajouter">Ajouter</button>
            </div>
            <?php if (!empty($listeFHF)) { ?>
        </section>
        </form>
    </div>
</div>
<!--    <div class="container">-->
<!-- Affichage des Frais Hors Forfait du mois en cours -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <p class="col-lg-12 bande text-left police-white">FRAIS HORS FORFAIT</p>
            <table class="table table-striped ">
                <tr>
                    <th>Mois</th>
                    <th>Libelle</th>
                    <th>Date justificatif</th>
                    <th>Montant</th>
                    <th colspan="2">Action</th>
                </tr>
                <tr>

                    <?php foreach ($listeFHF as $cle => $ligneFHF) { ?>
                    <form method="post" action="FormFHF.php">
                        <input type="hidden" id="libelle" name="id" value="<?php echo $listeFHF[$cle]['id']; ?>">
                        <td>  <?php echo $listeFHF[$cle]['mois']; ?> </td>
                        <td>  <?php echo $listeFHF[$cle]['libelleFrais']; ?> </td>
                        <input type="hidden" id="libelle" name="libelle"
                               value="<?php echo $listeFHF[$cle]['libelleFrais']; ?>">
                        <td><input type="date" class="form-control" id="datejustif" name="datejustif"
                                   value="<?php echo $listeFHF[$cle]['date']; ?>"></td>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" id="montant" name="montant"
                                       value="<?php echo $listeFHF[$cle]['montantFrais']; ?>">
                                <span class="input-group-addon">€</span</div>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-warning" type="submit" name="modifier" value="modifier">
                                <span class="glyphicon glyphicon-edit"></span> Modifier
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger" type="submit" name="supprimer" value="supprimer"
                                    onclick="return confirmer()">
                                <span class="glyphicon glyphicon-remove"></span> Supprimer
                            </button>
                        </td>
                        <input type="hidden" name="datemois" value="<?php echo $listeFHF[$cle]['mois']; ?>">
                </tr>
                </form>

                <?php } ?>
            </table>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>
