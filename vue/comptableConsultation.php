<?php
session_start();

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


        <p>Utilisateur</p>
        
        <form class="" action="comptableConsultation.php" method="POST">
 
                <?php
                
                $ligneFF = new Utilisateur();
                $lignenom = $ligneFF->selectNomPrenomConsulation();
                
                $maValeur = null;
                if (isset($_POST['id'])) {
                       $maValeur = $_POST["id"];
          
          
                     }
                ?>  

                <select name="id">
                    <?php foreach ($lignenom as $cle => $ligne) { ?>
                        <option value="<?php echo $lignenom[$cle]['idVisiteur'];?>"  
                                  <?php if ($maValeur == $lignenom[$cle]['idVisiteur']) { ?> selected<?php } ?> >  
                                  <?php echo $lignenom[$cle]['nom'] . ' ' . $lignenom[$cle]['prenom']; ?></option> 
                    <?php }  ?>
                </select>

                <!-- Button -->
                <div class="">
                    <label class="" for="chercher"></label>
                    <div class="">
                        <button id="chercher" type="submit" class="" name="chercher">chercher</button>
                    </div>
                </div>
        </form>
        
         <?php
        //Si les champ nom prenom ont une valeur on incruste les $_POST au $_SESSION
        //Pour pouvoir gardez les identifiant des visiteurs à chaque actualisation ou appuis de bouton de la page
        if (isset($_POST['chercher'])) {

            $visiteur = new Utilisateur();
            $visiteur->setId($_POST['id']);
            
            //Fonction pour recuperer les noms prenoms
            $ligneresu = $visiteur->selectCivil();

            $_SESSION['idVisiteur'] = $ligneresu['id'];
            $_SESSION['nomVisiteur'] = $ligneresu['nom'];
            $_SESSION['prenomVisiteur'] = $ligneresu['prenom'];
            
        }

        if (isset($_SESSION['nomVisiteur']) AND isset($_SESSION['prenomVisiteur'])) {
            $dateUs = date("Y/m/d");
            
            //ACTION pour modifier l'id etat 'VA' en 'MP'  
            if(isset($_POST['payement'])){
                
                $updateVA = new Fichefrais();
                
                $updateVA->setDateModif($dateUs);
                $updateVA->setIdVisiteur($_SESSION['idVisiteur']);
                $updateVA->setMois($_POST['mois']);
                
                $updateVA->updateFicheFraisVAenMP();          
            }
            
            //ACTION pour modifier l'id etat 'MP' en 'RB'  
            if(isset($_POST['rembourser'])){
                
                $updateVA = new Fichefrais();
                
                $updateVA->setDateModif($dateUs);
                $updateVA->setIdVisiteur($_SESSION['idVisiteur']);
                $updateVA->setMois($_POST['mois']);
                
                $updateVA->updateFicheFraisMPenRB();               
            }
            
            // Action pour Afficher les fiches frais ou idetat = VA ou MP
            $ligneFF = new Fichefrais();
            $ligneFF->setIdVisiteur($_SESSION['idVisiteur']);

            $listeFF = $ligneFF->selectFicheVAMP();
        }


         ?>
        <!-- n'affiche rien si le comptable n'a pas selectionné de visieur  -->
        <?php if (!empty($listeFF)) {?>
            
       
        </form>   
        <!-- Affichage des Frais au Forfait pour le mois séléctionné -->
        <table border='1'  width='70%'>
            <p>Consultation des fiches</p>
            <tr>
                <th>Id</th>
                <th>Nom</th>
                <th>Prénom</th> 
                <th>Total</th> 
                <th>Date</th> 
                <th>Etat</th> 
                <th colspan="2">Mise en payement/Rembourser</th>
            </tr>
            <tr>
                <!-- TABLEAU liste frais forfait -->
                <?php foreach ($listeFF as $cle => $ligneFF) { ?>
            <form method="post" action="comptableConsultation.php"> 
                    <td><?php echo $listeFF[$cle]['idVisiteur']; ?> </td>                   
                    <td><?php echo $listeFF[$cle]['nom']; ?> </td> 
                    <td><?php echo $listeFF[$cle]['prenom']; ?> </td>
                    <td><?php echo $listeFF[$cle]['montantValide']; ?> </td>
                    <td><?php echo $listeFF[$cle]['mois']; ?> </td> 
                    <input name="mois" type="hidden" value="<?php echo $listeFF[$cle]['mois']; ?>"> 
                    <td><?php echo $listeFF[$cle]['idEtat']; ?> </td>
                    <td><button id="" name="payement" class="">M/P</button></td>
                    <td><button id="" name="rembourser" class="">RB</button></td>
                    
                </form>
            </tr>
                    
                    

            </tr>

        <?php } ?>      
    </table>
    
    <?php  }?>
</body>
</html>
