<?php $title_for_layout = ($this->request->data['id'] == $_SESSION['user']['id']) ? 'Modification de votre profil' : 'Modification du profil de ' . $this->request->data['username']; ?>

<div class="page-header">
	<h1>
		<?php echo $title_for_layout; ?>
		<div class="pull-right">
			<?php
				if($this->request->data['id'] == $_SESSION['user']['id'])
					echo $this->Html->link(
						'<i class="icon-remove icon-white"></i> Supprimer mon compte',
						'#supprCompte',
						array('class' => 'btn btn-danger', 'data-toggle' => 'modal'));
			?>
		</div>
	</h1>
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

<div class="modal hide fade" id="supprCompte">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3><i class="icon-exclamation-sign warnIconModal"></i> Attention</h3>
	</div>
	<div class="modal-body">
		<p>Êtes-vous certain de voulour supprimer votre compte ?<br>Cette action est <strong>irréversible</strong></p>
	</div>
	<div class="modal-footer">
		<?php echo $this->Html->link(
			'Supprimer mon compte',
			array('controller' => 'users', 'action' => 'supprimer'),
			array('class' => 'btn btn-danger')
		); ?>
		<a class="btn" data-dismiss="modal">Annuler</a>
	</div>
</div>