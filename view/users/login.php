<?php $title_for_layout = 'Connexion'; ?>

<div class="page-header">
	<h1><?php echo $title_for_layout; ?></h1>
</div>

<?php

	$this->Form->create();
		$this->Form->input('Nom d\'utilisateur', 'username');
		$this->Form->input('Mot de passe', 'password', array('type' => 'password'));
	$this->Form->end();

?>