<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
	
	$ID_PERSONNE_ok = 0;
	
	# On récupère l'id_personne fournit et on va récupérer toutes ses infos personnelles.
	if(isset($_GET['id_personne']) && is_numeric($_GET['id_personne'])){
		
		$ID_PERSONNE_ok = 1;# On valide le fait que l'ID_PERSONNE a bien été réceptionné.
		
		$ID_PERSONNE = (int)$_GET['id_personne'];
		
		$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
		
		$personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# Ensuite on récupère ses IPs.
		
		$ip_personne = $oPCS_personne->fx_recuperer_toutes_ip_by_ID_PERSONNE($oMSG)->getData(1);
		
		# On récupère le parrain.
		$oMSG->setData('ID_PERSONNE', $personne[0]['PARRAIN']);
		$parrain = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On récupère les statuts.
		$oMSG->setData('ID_FAMILLE_TYPES', 'Statut professionnel');
		$statuts = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On récupère les rôles possibles d'un prestataire.
		$oMSG->setData('ID_FAMILLE_TYPES', 'Role');
		
		$roles = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On gère l'url de la vidéo.
		require_once('couche_metier/CL_video.php');
		$oCL_video = new CL_video();
		
		$personne[0]['CV_VIDEO'] = $oCL_video->fx_recuperer_tag($personne[0]['CV_VIDEO']);
		
		# On met en forme la date qui vient de la BDD.
		$personne[0]['DATE_NAISSANCE'] = $oCL_date->fx_convertir_date($personne[0]['DATE_NAISSANCE']);
		
		# On vire les balises <br /> des textarea.
		$personne[0]['DESCRIPTION'] = str_replace('<br />', '', $personne[0]['DESCRIPTION']);
		$personne[0]['TARIFS'] = str_replace('<br />', '', $personne[0]['TARIFS']);
		$personne[0]['MATERIEL'] = str_replace('<br />', '', $personne[0]['MATERIEL']);
				
		# On extrait les rôles de l'utilisateur.
		$ROLES = explode(',', $personne[0]['ROLES']);
	}
	
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>