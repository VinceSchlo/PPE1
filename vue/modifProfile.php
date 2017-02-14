<?php
session_start();
?>
<?php
require_once('../class/utilisateur.class.php');
require_once('../includes/dao.inc.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
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
</head>
<body>

<?php include("../includes/headerVisiteur_CSS.php"); ?>

<?php
// MODIFICATION DU PROFILS

if (isset($_POST['modifier'])) {

    $dao = new Dao();

    $util = new Utilisateur();

    $util->setId($_SESSION['id']);
    $util->setNom($_SESSION['nom']);
    $util->setPrenom($_SESSION['prenom']);
    $util->setLogin($_SESSION['login']);
    $util->setMdp($_POST['mdp']);
    $util->setAdresse($_POST['adresse']);
    $util->setVille($_POST['ville']);
    $util->setCp($_POST['cp']);

    $util->updateProfile();

    // CHANGEMENT DES NOUVELLES INFORMATIONS DE L'UTILISATEUR POUR LE NOUVELLE AFFICHAGE


    $_SESSION['ville'] = $_POST['ville'];
    $_SESSION['adresse'] = $_POST['adresse'];
    $_SESSION['cp'] = $_POST['cp'];
    $_SESSION['mdp'] = $_POST['mdp'];
}
?>

<!--FORNULAIRE UTILISATEUR -->
<div class="container">
    <div class="col-lg-6">
        <h1 class="title">Profil</h1>
        <img class="barre" src="../images/barre.PNG">

        <form class="form-horizontal" action="modifProfile.php" method="post">
            <!--INFORMATION NON MODIFIABLE -->

            <div class="form-group"><label class="control-label col-lg-3">Login :</label><?php echo $_SESSION['login']; ?> </div>

            <div class="form-group"><label class="control-label col-lg-3">Nom :</label><?php echo $_SESSION['nom']; ?> </div>

            <div class="form-group"><label class="control-label col-lg-3">Prenom :</label><?php echo $_SESSION['prenom']; ?> </div>


            <!--INFORMATION MODIFIABLE -->
            <div class="form-group">
                <label class="control-label col-lg-3" for="adresse">Adresse :</label>
                <input class="form-control col-lg-9" type="text" name="adresse" id="mdp"
                       value="<?php echo $_SESSION['adresse']; ?>"/>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3" for="mdp">Ville :</label>
                <input class="form-control col-lg-9" type="text" name="ville" id="ville"
                       value="<?php echo $_SESSION['ville']; ?>"/>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3" for="mdp">Code postal :</label>
                <input class="form-control col-lg-9" type="text" name="cp" id="mdp"
                       value="<?php echo $_SESSION['cp']; ?>"/>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3" for="mdp">Mot de passe :</label>
                <input class="form-control col-lg-9" type="text" name="mdp" id="mdp"
                       value="<?php echo $_SESSION['mdp']; ?>"/>
            </div>

            <div class="col-sm-offset-8 col-sm-3">
                <button class="btn btn-success" type="submit" name="modifier">Modifier</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>



