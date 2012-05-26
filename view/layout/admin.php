<!DOCTYPE html>
<html lang="fr">

	<head>
		<?php echo $this->Html->charset(); ?>
		<?php echo $this->Html->css('bootstrap'); ?>
		<?php echo $this->Html->css('responsive'); ?>
		<title><?php echo $title_for_layout; ?></title>
	</head>

	<body>

		<div class="container">

			<div class="navbar navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container-fluid">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<?php echo $this->Html->link('Series', '/', array('class' => 'brand')); ?>
						<div class="nav-collapse">
							<ul class="nav">
								<li class="active"><a href="#">METTRE</a></li>
								<li><a href="#">DES</a></li>
								<li><a href="#">LIENS</a></li>
								<li><a href="#">IÇI</a></li>
							</ul>
							<div class="btn-group pull-right">
								<a href="#" class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> <?php echo $_SESSION['user']['username']; ?> <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<?php echo $this->Html->link('<i class="icon-user"></i>&nbsp;&nbsp;Afficher mon profil',
											array('controller' => 'users', 'action' => 'profil')); ?>
									</li>
									<li>
										<?php echo $this->Html->link('<i class="icon-pencil"></i>&nbsp;&nbsp;Modifier mon profil',
											array('controller' => 'users', 'action' => 'edit')); ?>
									</li>
									<li class="divider"></li>
									<li>
										<?php echo $this->Html->link('<i class="icon-list"></i>&nbsp;&nbsp;Liste des membres</a>',
											array('controller' => 'users', 'action' => 'list')); ?>
									</li>
									<li class="divider"></li>
									<li>
										<?php echo $this->Html->link('<i class="icon-off"></i>&nbsp;&nbsp;Me déconnecter',
											array('controller' => 'users', 'action' => 'logout')) ?>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php $this->Session->flashes(); ?>
			<?php echo $content_for_layout; ?>

			<hr>
			<footer>
				&copy; Vincent Dimper et Mickaël Bourgier
			</footer>
		</div>

		<?php
			echo $this->Html->js('jquery');
			echo $this->Html->js('bootstrap-transition');
			echo $this->Html->js('bootstrap-alert');
			echo $this->Html->js('bootstrap-modal');
			echo $this->Html->js('bootstrap-dropdown');
			echo $this->Html->js('bootstrap-scrollspy');
			echo $this->Html->js('bootstrap-tab');
			echo $this->Html->js('bootstrap-tooltip');
			echo $this->Html->js('bootstrap-popover');
			echo $this->Html->js('bootstrap-button');
			echo $this->Html->js('bootstrap-collapse');
			echo $this->Html->js('bootstrap-carousel');
			echo $this->Html->js('bootstrap-typeahead');
			echo $this->Html->js('application');
		?>

	</body>

</html>