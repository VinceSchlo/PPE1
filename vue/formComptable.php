<?php
session_start();
?>

<?php
require_once ('../class/LigneFF.class.php');
require_once ('../class/LigneFHF.class.php');
require_once ('../includes/dao.inc.php');
require_once ('../class/utilisateur.class.php');
require_once ('../class/fichefrais.class.php');
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="../includes/libs.js"></script>
        <link rel="stylesheet" href="../css/formComptable.css" />
    </head>
    <body>

        <!--en téte de la page--> 
        <?php include ("../includes/headerComptable_CSS.php"); ?>

        <?php
        //Si les champ nom prenom ont une valeur on incruste les $_POST au $_SESSION
        //Pour pouvoir gardez les identifiant des visiteurs à chaque actualisation ou appuis de bouton de la page
        if (isset($_POST['id'])) {

            $visiteur = new Utilisateur();
            $visiteur->setId($_POST['id']);

            $ligneresu = $visiteur->selectCivil();

            $_SESSION['idVisiteur'] = $ligneresu['id'];
            $_SESSION['nomVisiteur'] = $ligneresu['nom'];
            $_SESSION['prenomVisiteur'] = $ligneresu['prenom'];
            $_SESSION['dateVisiteur'] = $_POST['date'];
        }



        if (isset($_SESSION['nomVisiteur']) AND isset($_SESSION['prenomVisiteur']) AND isset($_SESSION['dateVisiteur'])) {


            // Action pour modifier les Frais forfait du mois en cours
            if (isset($_POST['modifier'])) {
                //Vérification si l'utilisateur rentre une donnée numérique
                if (is_numeric($_POST['quantite'])) {

                    $modifLigneFF = new LigneFF();

                    $modifLigneFF->setIdFraisForfait($_POST['idFraisForfait']);
                    $modifLigneFF->setIdVisiteur($_SESSION['idVisiteur']);
                    $modifLigneFF->setMois($_SESSION['dateVisiteur']);
                    $modifLigneFF->setQuantite($_POST['quantite']);

                    $modifLigneFF->updateLFF();
                    ?><script>alert('La mofification à bien été prie en compte')</script> <?php
                } else {
                    ?> <script>alert('Veuiller rentrer une valeur numérique')</script> <?php
                }
            }
            // Action pour modifier les frais hors forfaits du mois en cours
            if (isset($_POST['modifierFHF'])) {
                //Vérification si l'utilisateur rentre une donnée numérique
                if (is_numeric($_POST['montantFHF'])) {
                    $modifLigneFHF = new ligneFHF();

                    $modifLigneFHF->setId($_POST['idFHF']);
                    $modifLigneFHF->setLibelleFrais($_POST['libelleFHF']);
                    $modifLigneFHF->setMontantFrais($_POST['montantFHF']);
                    $modifLigneFHF->setMois($_SESSION['dateVisiteur']);
                    $modifLigneFHF->setIdVisiteur($_SESSION['idVisiteur']);

                    $modifLigneFHF->updateLigneFHF();
                    ?><script>alert('La mofification à bien été prie en compte')</script> <?php
                } else {
                    ?> <script>alert('Veuiller rentrer une valeur numérique')</script> <?php
                }
            }

            //ACTION du bouton refuser pour rajouter le mot refuser devant le libelle
            if (isset($_POST['refuser'])) {
                //On verifie si REFUSE est dejà existant dans le libelle pour le rajouter
                //si le mot est dàja existant on n'exécute pas la requéte
                if (stristr($_POST['libelleFHF'], 'Refuse') === FALSE) {
                    $refuser = 'Refuse : ' . $_POST['libelleFHF'];

                    $modifLigneFHF = new ligneFHF();

                    $modifLigneFHF->setId($_POST['idFHF']);
                    $modifLigneFHF->setLibelleFrais($_POST['libelleFHF']);
                    $modifLigneFHF->setMontantFrais($_POST['montantFHF']);
                    $modifLigneFHF->setMois($_SESSION['dateVisiteur']);
                    $modifLigneFHF->setIdVisiteur($_SESSION['idVisiteur']);

                    $modifLigneFHF->updateLigneCFHF($refuser);
                }
            }

            //Action reporter pour rajouter 1 mois à chaque appuis du bouton

            if (isset($_POST['reporter'])) {
                if (stristr($_POST['moisFHF'], '12') === FALSE) {
                    $_POST['moisFHF'] += 1;
                } else
                    $_POST['moisFHF'] += 89;

                $fichefrais = new Fichefrais();

                $idEtat = "CR";

                //Récuperer la date en US.
                $dateUs = date("Y/m/d");

                $fichefrais->setIdVisiteur($_SESSION['idVisiteur']);
                $fichefrais->setMois($_POST['moisFHF']);
                $fichefrais->setIdEtat($idEtat);
                $fichefrais->setDateModif($dateUs);

                $fichefrais->verifierFicheFrais();


                $modifLigneFHF = new ligneFHF();

                $modifLigneFHF->setId($_POST['idFHF']);
                $modifLigneFHF->setLibelleFrais($_POST['libelleFHF']);
                $modifLigneFHF->setMontantFrais($_POST['montantFHF']);
                $modifLigneFHF->setMois($_POST['moisFHF']);
                $modifLigneFHF->setIdVisiteur($_SESSION['idVisiteur']);

                $modifLigneFHF->updateLigneMoisCFHF();
            }

            //ACTION pour revenir a la valeur présédente 
            if (isset($_POST['annuler'])) {
                header("Location:formComptable.php");
            }




            // Action pour Afficher les frais forfaits et frais hors forfaits du mois en cours
            $ligneFF = new Utilisateur();

            $ligneFF->setIdVisiteur($_SESSION['idVisiteur']);
            $ligneFF->setMois($_SESSION['dateVisiteur']);
            // Selection de tous les Frais Forfait 
            // on affecte les valeurs dans $listeFF pour les exploiter dans un tableaux
            $listeFF = $ligneFF->selectLFFC();
            // Selection de tous les Frais HORS Forfait
            $listeFHF = $ligneFF->selectLFHFC();
        }
        ?>







        <!-- CORPS DE PAGE -->
        <div class="corp_all"> 



            <div class="corps_2">
                <div class="section1">
                    <form class="form-horizontal" action="formComptable.php" method="POST" >

                        <?php
                        $moisCours = date("d-m-Y");
                        $mois = substr($moisCours, 6) . substr($moisCours, 3, 2);

                        // Récuperer la date : Y-m-d
                        $date = date("Y-m-d");

                        $datejour = substr($date, -2);

                        if ($datejour >= 10) {

                            $cloture = new Fichefrais();

                            $cloture->setMois($mois);

                            $cloture->clotureFicheDix();
                        }

                        $ligneFF = new Utilisateur();
                        $lignenom = $ligneFF->selectNomPrenom();
                        ?>

  
                        
                        <section>
                            <p>Utilisateur</p>
                            <div class="utitlisateur">
                                
                                <div class="selectUtilisateur">
                                    <p>Nom Prénom :</p>
                                    <select name="id">
                                        <?php foreach ($lignenom as $cle => $ligne) { ?>
                                            <option  value="<?php echo $lignenom[$cle]['idVisiteur']; ?>"> <?php echo $lignenom[$cle]['nom'] . ' ' . $lignenom[$cle]['prenom']; ?></option> 
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="selectMois">
                                    <!-- MENU DES MOIS-->
                                    <div class="moisVisiteur">
                                        <p>Mois :</p>
                                        <select id="consultation" name="date" class="form-control">
                                            <option value="201601">Janvier</option>
                                            <option value="201602">Février</option>
                                            <option value="201603">Mars</option>
                                            <option value="201604">Avril</option>
                                            <option value="201605">Mai</option>
                                            <option value="201606">Juin</option>
                                            <option value="201607">Juillet</option>
                                            <option value="201608">Août</option>
                                            <option value="201609">Septembre</option>
                                            <option value="201610">Octobre</option>
                                            <option value="201611">Novembre</option>
                                            <option value="201612">Décembre</option>
                                        </select>
                                    </div>
                                    <!-- Button -->
                                    <div class="form-group">
                                        <label  for="chercher"></label> 
                                        <button id="chercher" type="submit" class="btn btn-primary">chercher</button>       
                                    </div>
                                </div>
                            </div>
                    </form>




                    <?php
                    //Pour ne pas afficher 2 fois les nom prenom visiteur
                    //On affiche l'id le nom et prenom en fonction si on trouve des valeurs dans $listeFF et $listeFHF
                    if (!empty($listeFF) OR ! empty($listeFHF)) {
                        if (!empty($listeFF)) {
                            echo $_SESSION['idVisiteur'] . ' - ' . $_SESSION['nomVisiteur'] . ' ' . $_SESSION['prenomVisiteur'];
                        } else {
                            echo $_SESSION['idVisiteur'] . ' - ' . $_SESSION['nomVisiteur'] . ' ' . $_SESSION['dateVisiteur'];
                        }
                    }
                    ?>

                    <!--//On affiche les résultat que si la variable contient des informations-->
                    <?php if (!empty($listeFF)) {
                        ?>

                        <!-- Affichage des Frais au Forfait pour le mois séléctionné -->
                        <p>Frais Forfait</p>
                        <div class="partie1">
                            <article class="fraisAuForfait" >
                                <table border='1'  width='70%' class="tableauFF" >                  

                                    <tr>
                                        <th>Mois</th>
                                        <th>Type Frais</th>
                                        <th>Quantité</th> 
                                        <th colspan="2">Action</th> 
                                    </tr>
                                    <tr>
                                        <!-- TABLEAU liste frais forfait -->
                                        <?php foreach ($listeFF as $cle => $ligneFF) { ?>
                                        <form method="post" action="formComptable.php"> 

                                            <td>  <?php echo $listeFF[$cle]['mois']; ?> </td>
                                            <td> <?php echo $listeFF[$cle]['idFraisForfait']; ?> </td>
                                            <!-- HIDDEN récupére les informations de SQL pour la récupérer avec $_POST pour garnir les variables -->
                                            <input name="idFraisForfait" type="hidden" value="<?php echo $listeFF[$cle]['idFraisForfait']; ?>"> 
                                            <td><input name="quantite" type="text" value="<?php echo $listeFF[$cle]['quantite']; ?>"> </td> 
                                            <div class="allBouton1">
                                                <td><button id="modifier" name="modifier" class="boutonmodifier">Modifier</button></td> 
                                                <td><button id="refuser" name="annuler" class="boutonannuler">Annuler</button></td>
                                            </div>
                                        </form>
                                        </tr>

                                    <?php } ?>      
                                </table>
                            <?php } ?>
                        </article>

                        <!-- On affiche les résultat que si la variable contient des informations -->
                        <?php if (!empty($listeFHF)) { ?>

                            <!-- Affichage des Frais HORS Forfait pour le mois séléctionné -->
                            <p>Frais Hors Forfait</p>
                            <article class="fraisHorsForfait">
                                <table border='1'  width='70%' class="tableauFHF">

                                    <tr>
                                        <th>Mois</th>
                                        <th>Libelle</th>
                                        <th>Montant</th> 
                                        <th colspan="4">modifier/reporter/annuler/refuser</th>
                                    </tr>
                                    <tr>     
                                        <!-- TABLEAU liste frais hors forfait -->
                                        <?php foreach ($listeFHF as $cle => $ligneFHF) { ?>
                                        <form method="post" action="formComptable.php">  

                                            <td> <?php echo $listeFHF[$cle]['mois']; ?> </td>
                                            <!-- HIDDEN récupére les informations de SQL pour la récupérer avec $_POST pour garnir les variables -->
                                            <input name="moisFHF" type="hidden" value="<?php echo $listeFHF[$cle]['mois']; ?>">  
                                            <td> <?php echo $listeFHF[$cle]['libelleFrais']; ?> </td>
                                            <input name="idFHF" type="hidden" value="<?php echo $listeFHF[$cle]['id']; ?>"> 
                                            <input name="libelleFHF" type="hidden" value="<?php echo $listeFHF[$cle]['libelleFrais']; ?>">   
                                            <td><input name="montantFHF" type="text" value="<?php echo $listeFHF[$cle]['montantFrais']; ?>"> </td> 
                                            <!--BOUTON -->
                                            <div class="allBouton2">
                                                <td><button id="modifierFHF" name="modifierFHF" class="boutonmodifier">Modifier</button></td>               
                                                <td><button id="refuser" name="reporter" class="boutonreport"  >Reporter</button></td>
                                                <td><button id="refuser" name="annuler" class="boutonannuler">Annuler</button></td>
                                                <td><button id="refuser" name="refuser" class="boutonrefuser" onclick="return confirmer2()">Refuser</button></td>
                                            </div>
                                        </form>
                                        </tr>
                                    </table>
                                    <?php
                                }
                            }
                            ?>
                        </article>   

                        </section>

                    </div>
                    <div class="section2">
                        <aside>
                            <div class="calendier">
                                <p><img src="../images/calend2.png" ></p>
                            </div>
                            <div class="infonews">
                                <p><img src="../images/infonews2.png" ></p>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
            <footer>
                <p>frfoém</p>
            </footer>


    </body>
</html>
