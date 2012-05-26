<?php $title_for_layout = ($this->request->data['id'] == $_SESSION['user']['id']) ? 'Modification de votre profil' : 'Modification du profil de ' . $this->request->data['username']; ?>

<div class="page-header">
	<h1><?php echo $title_for_layout; ?></h1>
</div>

<div class="row">
	<div class="span12">
		<?php
			$this->Form->create();
				if($_SESSION['user']['status'] == 'Administrateur')
					$this->Form->input('Nom d\'utilisateur', 'username');
				$this->Form->input('Adresse email', 'mail');
				echo '<br>';
				$this->Form->input('Mot de passe', 'newPass1', array('type' => 'password'));
				$this->Form->input('Confirmation du mot de passe', 'newPass2', array('type' => 'password'));
				$this->Form->input('Ancien mot de passe', 'oldPass', array('type' => 'password'));
			$this->Form->end('Modifier');
		?>
	</div>
</div>