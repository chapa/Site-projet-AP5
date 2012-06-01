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
						<?php echo $this->Html->link('Watch your Series', '/', array('class' => 'brand')); ?>
					</div>
				</div>
			</div>

			<?php
				echo (Conf::$debug) ? $content_for_layout : 'La page que vous recherchez n\'existe pas';
			?>

			<hr>
			<footer>
				Créé par 
				<?php echo $this->Html->link('Vincent Dimper', 'mailto:vincent.dimper@tulaurajamais.com', array('rel' => 'tooltip', 'data-original-title' => 'Envoyer un mail à Vincent')); ?>
				et
				<?php echo $this->Html->link('Mickaël Bourgier', 'mailto:mickael.bourgier@cellelanonplus.com', array('rel' => 'tooltip', 'data-original-title' => 'Envoyer un mail à Mickaël')); ?>,
				date de dernière mise à jour : le 1 juin 2012
				<div class="pull-right">
					<a href="http://validator.w3.org/check?uri=referer">
						<img src="http://www.w3.org/html/logo/badge/html5-badge-h-css3-device-semantics.png" height="32" alt="HTML5 Powered with CSS3 / Styling, Device Access, and Semantics" title="HTML5 Powered with CSS3 / Styling, Device Access, and Semantics">
					</a>
				</div>
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

			if(Conf::$debug)
			{
				global $timeStart;
				echo '<div class="alert alert-block alert-success fade in" style="position:fixed;bottom:0;left:0;right:0;margin:10px;"><a href="#" class="close" data-dismiss="alert">&times;</a>Page généré en ' . round(microtime(true) - $timeStart, 5) . ' secondes</div>';
			}
		?>

	</body>

</html>