<?php

	$this->Form->create();
		$this->Form->input('Nom d\'utilisateur', 'username');
		$this->Form->input('Mail','Mail');

		$this->Form->input('Mot de passe', 'password1', array('type' => 'password'));
		$this->Form->input('Retapez le mot de passe', 'password2', array('type' => 'password'));
	$this->Form->end();

?>