<?php
session_start();
?>

<!DOCTYPE html>
<?php
require_once ('../includes/dao.inc.php');
require_once ('../class/utilisateur.class.php');
?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>GSB</title>

        <link rel="stylesheet" href="../css/index1.css" />
    </head>
    <body>
        <div id="bloc_page">

            <?php
            
            // Verification qu'un login et un pass a été saisis.
            
            if (isset($_POST['login']) && isset($_POST['mdp'])) {

                // Création objet $connection de la class Utilisateur
                $connexion = new Utilisateur();

                // On résupère le login et le mdp saisie pas l'utilisateur
                $connexion->setLogin($_POST["login"]);
                $connexion->setMdp($_POST["mdp"]);

                // On éxécute la fonction pour vérifier si l'utilisateur a rentré les bonnes informations
                $user = $connexion->connectionUtilisateur();

                // Si l'utlisateur n'éxiste pas retour a l'index
                if (!isset($user)) {
                    //Si idientifiant ou mdp faux alert JAVAscript 
                    ?><script>alert('Mauvais identifiants ou mdp')</script> <?php
        
                } else {
                    // Si l'utilisateur existe garnir la varriable $_SESSION
                    $_SESSION = $user;

                    // Envoie vers la page en fonction du type d'utilisateur
                    if ($_SESSION['idType'] == "V") {
                        header("Location:formFF.php");
                    }
                    if ($_SESSION['idType'] == "C") {
                        header("Location:formComptable.php");
                    }
                }
            } else
                
                ?>


            <header>
                <!-- Formulaire de connexion des utilisateurs -->


                <div class="formulaire">


                    <form class="form" action="index1.php" method="POST">


                        <!-- Login -->


                        <label class="id_connexion" for="login">Login</label>  

                        <input class="case_login" name="login" id="login" type="text">    


                        <!-- Password -->

                        <label class="mot_de_passe" for="mdp">Mdp</label>

                        <input class="case_mdp" name="mdp" id="mdp" type="password"> 


                        <!-- Valider -->

                        <button class="valider" name="valider">Ok</button>

                    </form>

                </div>

            </header>

            <section id="bloc1">

                <div class="img_medic">

                    <img id="img_medicaments" src="../images/medicaments.jpg">

                    <div class="title">
                        <h3 class="titre">GSB</h3>
                    </div>
                    <p class="texte">Le laboratoire Galaxy Swiss Bourdin (GSB) est issu de la fusion entre le géant américain
                        Galaxy (spécialisé dans le secteur des maladies virales dont le SIDA et les hépatites) et le
                        conglomérat européen Swiss Bourdin (travaillant sur des médicaments plus conventionnels),
                        lui-même déjà union de trois petits laboratoires.</p>

                </div>


                <?php include '../includes/calendrier_CSS.php' ?>

            </section>

            <?php include '../includes/piedPage_CSS.php' ?>

        </div>

    </body>
</html>
