

<link rel="stylesheet" href="../css/headerComptable.css" />
<header>
    
    <div class="header1">
        <div class="logo">
            <img src="..//images/logoGSB.png">
        </div>
        <div class="headerMessage">
            <p><?php echo "Bonjour,  " . $_SESSION['nom'] . "  " . $_SESSION['prenom'] . "<br />"; ?></p>
        </div>
        <div class="headerDeco">
            <p>Role : Comptable</p>
            <a class="Deconnexion" href="deconnexion.php">Déconnexion</a>
        </div >
    </div>
    <div class="header2">
        <ul class="headerNav">
            <li><a class="lien" href="formComptable.php">Fiche</a></li>
            <li><a class="lien" href="comptableValider.php">Valider</a></li>
            <li ><a class="lien" href="comptableConsultation.php">Consultation</a></li>
        </ul>
    </div>
</header>