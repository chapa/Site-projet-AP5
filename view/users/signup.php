<?php $title_for_layout = 'Inscription'; ?>

<div class="page-header">
	<h1><?php echo $title_for_layout; ?></h1>
</div>

<?php

	$this->Form->create();
		$this->Form->input('Nom d\'utilisateur', 'username');
		$this->Form->input('Mail','mail');
		echo '<br>';
		$this->Form->input('Mot de passe', 'password1', array('type' => 'password'));
		$this->Form->input('Retapez le mot de passe', 'password2', array('type' => 'password'));
	$this->Form->end();

?>