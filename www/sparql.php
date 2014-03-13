<?php

// ceci est un controleur global, il regarde si une section est spécifée, et il appel les bon controleurs en fonction
// de la section demandée, si rien est spécifié, il appel le controleur accueil, qui affiche la page d'accueil.

if (!isset($_GET['section']))
{
    include_once('controleur/accueil.php');
}
else
{
	if ($_GET['section'] == 'statistique')
	{
		include_once('controleur/statistique.php');
	}
}