<?php
session_start();
?>
<?php
require_once('../includes/dao.inc.php');
require_once('../class/ligneFF.class.php');
require_once('../class/fichefrais.class.php');
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

<!--En-tête page-->
<?php include '../includes/headerVisiteur_CSS.php'; ?>

<?php
// Récuperer la date : YYYYMM
$date = date("d-m-Y");
$mois = substr($date, 6) . substr($date, 3, 2);

//Récuperer la date en US.
$dateUs = date("Y/m/d");

// Action pour supprimer les LFF du mois en cours
if (isset($_POST['supprimer'])) {
    $supprimer = new LigneFF();

    $supprimer->setIdFraisForfait($_POST['idFraisForfait']);
    $supprimer->setMois($mois);
    $supprimer->setQuantite($_POST['quantite']);

    $supprimer->deleteLFF();
}

// Action pour modifier les LFF du mois en cours
if (isset($_POST['modifier'])) {

    if (is_numeric($_POST['quantite'])) {

        $modifLigneFF = new LigneFF();

        $modifLigneFF->setIdVisiteur($_SESSION['id']);
        $modifLigneFF->setMois($mois);
        $modifLigneFF->setIdFraisForfait($_POST['idFraisForfait']);
        $modifLigneFF->setQuantite($_POST['quantite']);

        $modifLigneFF->updateLFF();
        ?>
        <script>alert('La mofification à bien été prie en compte')</script> <?php
    } else {
    ?>
        <script>alert('Veuiller rentrer une valeur numérique')</script> <?php
    }
}

// Action pour inserer une nouvelle ligne Frais Forfait
//Si le visiteur selectionne Type frais on n'éxecute pas la requéte
if (isset($_POST['valider'])) {
    //Vérification si l'utilisateur rentre une donnée numérique
    if (is_numeric($_POST['quantite'])) {

    if (stristr($_POST['idFraisForfait'], 'typefrais') === FALSE) {

        $fichefrais = new Fichefrais();

        $idEtat = "CR";

        $fichefrais->setIdVisiteur($_SESSION['id']);
        $fichefrais->setMois($mois);
        $fichefrais->setIdEtat($idEtat);
        $fichefrais->setDateModif($dateUs);

        $fichefrais->verifierFicheFrais();

        $ajoutLigneFF = new LigneFF();

        $ajoutLigneFF->setIdVisiteur($_SESSION['id']);
        $ajoutLigneFF->setMois($mois);
        $ajoutLigneFF->setIdFraisForfait($_POST['idFraisForfait']);
        $ajoutLigneFF->setQuantite($_POST['quantite']);


        $resu = $ajoutLigneFF->insertLFF();

        // ACTION pour rajouter une fiscaliter de véhicule
        if ($_POST['idFraisForfait'] == 'KM') {

            $idTypeVehicule = $_POST['typeVeh'];
            $fichefraisVehicule = new Fichefrais();

            $fichefraisVehicule->setIdVisiteur($_SESSION['id']);
            $fichefraisVehicule->setMois($mois);
            $fichefraisVehicule->setIdTypeVehicule($idTypeVehicule);

            $fichefraisVehicule->updateFicheFraisCV();
        }

        $cloture = new Fichefrais();

        $cloture->setMois($mois);
        $cloture->setIdVisiteur($_SESSION['id']);

        $cloture->clotureFicheSaisie();
    } else {
        ?>
        <script>alert('Veuiller choisir le type de frais')</script> <?php
    }
    } else {
    ?>
        <script>alert('Veuiller rentrer une valeur numérique')</script> <?php
    }
}


// Action pour selectionner les LFF du mois en cours
$select = new LigneFF();

$select->setIdVisiteur($_SESSION['id']);
$select->setMois($mois);

$afficher = $select->selectLFF();
?>

<div class="container-fluid">
    <div class="row">
        <section class="col-xs-offset-1 col-xs-4">
            <h1 class="title">Frais au forfait</h1>
            <img id="barre" src="../images/barre.PNG">

            <p>Nom : <?php echo $_SESSION['nom']; ?></p>
            <p>Prénom : <?php echo $_SESSION['prenom']; ?></p>


            <!-- Formulaire pour inserer une LFF -->
            <form class="form-horizontal" action="formFF.php" method="POST">


                <div class="mois">
                    <h1 class="title"> Période d'engagement </h1>
                    <img id="barre" src="../images/barre.PNG">
                    <?php
                    $moisaffich = date("m");
                    $anneeaffich = date("Y");
                    ?>
                    <p>Mois : <?php echo $moisaffich; ?> </p>
                    <p>Année : <?php echo $anneeaffich; ?></p>
                </div>
        </section>
        <section class="col-xs-4">

            <!-- Section du Type  -->
            <h1 class="title">Ajout frais au forfait</h1>
            <img id="barre" src="../images/barre.PNG">
            <div class="form-group">
                <label class="control-label col-lg-offset-2 col-xs-3" for="idFraisForfait">Catégorie :</label>
                <div class="col-xs-5">
                    <!-- onchange="activeDesactiveTypeVeh()" apelle la fonction javascript pour afficher cacher le CV véhicule-->
                    <select id="idFraisForfait" name="idFraisForfait" class="form-control"
                            onchange="activeDesactiveTypeVeh()">
                        <option value="typefrais" selected="">Type Frais</option>
                        <option value="ETP">ETP</option>
                        <option value="KM">KM</option>
                        <option value="NUI">NUI</option>
                        <option value="REP">REP</option>
                    </select>
                </div>
            </div>
            <!-- Multiple Radios (inline) -->

            <!--Affiche les CV si KM est séléctionné-->
            <div class="col-md-6" id="divVeh" style='display:none;'>
                    <div class="radio pull-left ">
                        <label for="typVeh-0">
                            <input type="radio" name="typeVeh" id="typVeh-0" value="4cvd">
                            4 chevaux diesel
                        </label>
                    </div>
                    <div class="radio pull-left ">
                        <label for="typVeh-1">
                            <input type="radio" name="typeVeh" id="typVeh-1" value="4cve">
                            4 chevaux essence
                        </label>
                    </div>
                    <div class="radio pull-left">
                        <label for="typVeh-2">
                            <input type="radio" name="typeVeh" id="typVeh-2" value="56cvd">
                            5/6 chevaux diesel
                        </label>
                    </div>
                    <div class="radio pull-left">
                        <label for="typVeh-3">
                            <input type="radio" name="typeVeh" id="typVeh-3" value="56cve">
                            5/6 chevaux essence
                        </label>
                    </div>
            </div>
            <br/>

            <!-- choix de la quantité -->
            <div class="form-group">
                <label class="col-lg-offset-2 col-xs-3 control-label" for="quantite">Quantité :</label>
                <div class="col-xs-5">
                    <input id="quantite" name="quantite" type="text" placeholder="Quantite"
                           class="form-control"
                           required>
                </div>
            </div>


            <!-- Button pour finaliser la saisie -->
            <div class="btn_valider">
                <label class="col-md-4 control-label" for="valider"></label>
                <div class="col-md-4">
                    <button id="valider" name="valider" class="btn btn-primary">Ajouter</button>

                </div>
        </section>
    </div>


    </form>
</div>

<?php if (!empty($afficher)) { ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <p class="col-lg-12 bande text-left police-white">FRAIS AU FORFAIT</p>

            <table class="table table-striped" width="100%">
                <tr>
                    <th>Mois</th>
                    <th>Type frais</th>
                    <th>Quantité</th>
                    <th colspan="2">Action</th>
                </tr>

                <tr>

                    <!-- Tableau pour afficher et modifier les LFF du mois en cours -->
                    <?php foreach ($afficher as $cle => $ligneFF) { ?>
                    <form action="formFF.php" method="POST">
                        <td><?php echo $afficher[$cle]['mois']; ?> </td>
                        <td><?php echo $afficher[$cle]['idFraisForfait']; ?> </td>
                        <input name="idFraisForfait" type="hidden"
                               value="<?php echo $afficher[$cle]['idFraisForfait']; ?>">
                        <td><input class="form-control" name="quantite" type="text"
                                   value="<?php echo $afficher[$cle]['quantite']; ?>"></td>
                        <td>
                            <button id="modifier" name="modifier" class="btn btn-primary btn-warning"
                                    onclick="return modifier()">
                                <span class="glyphicon glyphicon-edit"></span> Modifier
                            </button>
                        </td>
                        <td>
                            <button id="supprimer" name="supprimer" class="btn btn-sm btn-danger"
                                    onclick="return confirmer()">
                                <span class="glyphicon glyphicon-remove"></span> Supprimer
                            </button>
                        </td>
                        <input type="hidden" name="datemois" value="<?php echo $afficher[$cle]['mois']; ?>">
                    </form>
                </tr>
                <?php } ?>
            </table>
        </div>

        <?php } ?>


    </div>
</body>
</html>
