<?php $title_for_layout = 'Mes séries'; ?>

<div class="hero-unit">
	<h1><?php echo $title_for_layout; ?></h1>
	<p>Vous trouverez ici toutes les séries que vous avez ajouté</p>
</div>

<?php foreach ($series as $s): ?>
	
	<section class="serie">
		<div class="row-fluid">
			<div class="span2">
				<?php echo $this->Html->image('poster.png', array('alt' => 'Poster de ' . $s['title'])); ?>
			</div>
			<div class="span10">
				<div class="page-header">
					<h1><?php echo $s['title']; ?></h1>
				</div>
				<p>
					<div class="progress progress-info progress-striped active">
						<div class="bar" style="width: <?php echo $s['progression'] ?>%;"></div>
						<div class="text"><?php echo $s['progression'] ?> %</div>
					</div>
				</p>
			</div>
		</div>
	</section>

<?php endforeach ?>

<?php debug($series); ?>