<link rel="stylesheet" href="../css/headerVisiteur.css" />

<header>

    <div class="header1">
        <div class="logo">
            <img src="..//images/logoGSB.png">
        </div>
        <div class="headerMessage">
            <p><?php echo "Bonjour,  " . $_SESSION['nom'] . "  " . $_SESSION['prenom'] . "<br />"; ?></p>
        </div>
        <div class="headerDeco">
            <p>Role : Consultant</p>
            <a class="Deconnexion" href="deconnexion.php">Deconnexion</a>
        </div >
    </div>
    <div class="header2">
        <ul class="headerNav">
            <li><a class="lien" href="formFF.php">Frais au forfait</a></li>
            <li><a class="lien" href="formFHF.php">Frais hors forfait</a></li>
            <li ><a class="lien" href="consultation.php">Consultation</a></li>
            <li ><a class="lien" href="modifProfile.php">Compte</a></li>
        </ul>
    </div>
</header>


