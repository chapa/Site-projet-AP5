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