<?php $title_for_layout = ($profil['id'] == $_SESSION['user']['id']) ? 'Votre profil' : 'Profil de ' . $profil['username']; ?>

<div class="page-header">
	<h1>
		<?php echo $title_for_layout; ?>
		<?php if($profil['id'] == $_SESSION['user']['id'] OR $_SESSION['user']['status'] == 'Administrateur') : ?>
			<div class="pull-right">
				<?php
					echo $this->Html->link(
						'<i class="icon-pencil"></i> Editer',
						array('controller' => 'users', 'action' => 'edit', $profil['id']),
						array('class' => 'btn'));
				?>
			</div>
		<?php endif ?>
	</h1>
</div>

<ul>
	<li>Identifiant: <strong><?php echo $profil['username']; ?></strong> </li>
	<li>Mail: <strong><?php echo $profil['mail']; ?></strong> </li>
	<li>Status: <strong><?php echo $profil['status']; ?></strong> </li>
	<li>Nombre d'épisodes vus: <strong><?php echo $profil['nbwatched']; ?></strong> </li>
	<li>Nombre de saisons regardés: <strong><?php echo $profil['nbwatch']; ?></strong> </li>
</ul>
