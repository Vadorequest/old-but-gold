<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
?>
	<img style="" alt="Informations personnelles"
 src="images/infos-personnelles.png">
<br />
<br />
	
	<?php
	require_once('include_form_modifier_fiche_membre.php');
	?>
	
<?php				
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>