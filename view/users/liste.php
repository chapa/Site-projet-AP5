<?php $title_for_layout = 'Liste des membres'; ?>

<div class="page-header">
	<h1><?php echo $title_for_layout; ?></h1>
</div>

<p>Il y a actuellement <strong><?php echo $nbMembres; ?></strong> membres</p>

<div class="usersTable">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Groupe</th>
				<th>Nom d'utilisateur</th>
				<th>Adresse mail</th>
				<th>Nombre de séries suivies</th>
				<?php if ($_SESSION['user']['status'] == 'Administrateur'): ?>
					<th style="width:50px;">Actions</th>
				<?php endif ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($membres as $v): ?>
				<tr>
					<td><?php echo $v['status']; ?></td>
					<td>
						<?php
							echo $this->Html->link(
								$v['username'],
								array('controller' => 'users', 'action' => 'profil',$v['id']),
								array('rel' => 'tooltip', 'data-original-title' => 'Afficher le profil de ' . $v['username'])
							);
						?>
					</td>
					<td>
						<?php
							echo $this->Html->link(
								$v['mail'],
								'mailto:' . $v['mail'],
								array('rel' => 'tooltip', 'data-original-title' => 'Envoyer un mail à ' . $v['username'])
							);
						?>
					</td>
					<td>
						<?php
							echo $this->Html->link(
								$v['nbseries'],
								array('controller' => 'series', 'action' => 'liste', $v['id']),
								array('rel' => 'tooltip', 'data-original-title' => 'Afficher les séries suivies par ' . $v['username'])
							);
						?>
					</td>
					<?php if ($_SESSION['user']['status'] == 'Administrateur'): ?>
						<td>
							<?php
								switch ($v['status'])
								{
									case 'Banni':
										echo $this->Html->link(
											'<i class="icon-ok-circle"></i>',
											array('controller' => 'users', 'action' => 'ban', 0, $v['id']),
											array('rel' => 'tooltip', 'data-original-title' => 'Débannir ' . $v['username'])
										);
										break;
									case 'Membre':
										echo $this->Html->link(
											'<i class="icon-ban-circle"></i>',
											array('controller' => 'users', 'action' => 'ban', 1, $v['id']),
											array('rel' => 'tooltip', 'data-original-title' => 'Bannir ' . $v['username'])
										) . '&nbsp;';
										echo $this->Html->link(
											'<i class="icon-upload"></i>',
											array('controller' => 'users', 'action' => 'admin', 1, $v['id']),
											array('rel' => 'tooltip', 'data-original-title' => 'Grader ' . $v['username'])
										);
										break;
									case 'Administrateur':
										echo $this->Html->link(
											'<i class="icon-ban-circle"></i>',
											array('controller' => 'users', 'action' => 'ban', 1, $v['id']),
											array('rel' => 'tooltip', 'data-original-title' => 'Bannir ' . $v['username'])
										) . '&nbsp;';
										echo $this->Html->link(
											'<i class="icon-download"></i>',
											array('controller' => 'users', 'action' => 'admin', 0, $v['id']),
											array('rel' => 'tooltip', 'data-original-title' => 'Dégrader ' . $v['username'])
										);
										break;
								}
							?>
						</td>
					<?php endif ?>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>