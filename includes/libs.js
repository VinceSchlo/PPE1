//Fonction pour activer l'affichage CV pour le visiteur -> formFF.php
function activeDesactiveTypeVeh() {
    var maPresta = document.getElementById("idFraisForfait").value;
	
	if (maPresta=="KM"){
	document.getElementById("divVeh").style.display="block";
	}
	else{
	document.getElementById("divVeh").style.display="none";
	}
	
	
}

function choixVisiteurChoisi(){

document.getElementById("formChoixVisiteur").submit();

}

function choixMoisChoisi(){

document.getElementById("formChoixMois").submit();

}

//fonction pour activer une fenétre  de suppression
function confirmer(){
    return confirm("Etes vous sur de vouloir supprimer ?");
}

//fonction pour activer une fenétre  de refusser pour comptable
function confirmer2(){
    return confirm("Etes vous sur de vouloir refusser ?");
}


