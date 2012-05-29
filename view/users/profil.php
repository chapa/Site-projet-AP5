<?php $title_for_layout = 'Profil'; ?>

<div class="page-header">
	<h1><?php echo $title_for_layout; ?></h1>
</div>

<ul type="1">
	<li>Identifiant: <strong><?php echo $profil['username']; ?></strong> </li>
	<li>Mail: <strong><?php echo $profil['mail']; ?></strong> </li>
	<li>Status: <strong><?php echo $profil['status']; ?></strong> </li>
	<li>Nombre d'épisodes vus: <strong><?php echo $profil['nbwatched']; ?></strong> </li>
	<li>Nombre de saisons regardés: <strong><?php echo $profil['nbwatch']; ?></strong> </li>
	
</ul>
