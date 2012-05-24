<?php

	$this->Form->create();
		$this->Form->input('Nom d\'utilisateur', 'username');
		$this->Form->input('Mot de passe', 'password', array('type' => 'password'));
	$this->Form->end();

?>