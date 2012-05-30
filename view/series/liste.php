<?php $title_for_layout = 'Mes séries'; ?>

<div class="hero-unit">
	<h1><?php echo $title_for_layout; ?></h1>
	<p>Vous trouverez ici toutes les séries que vous avez ajouté</p>
</div>

<?php foreach ($series as $v):
	$image = 'series' . DS . 'posters' . DS . $v['id'] . '.jpg';
	if(!is_file(WEBROOT . DS . 'img' . DS . $image))
		$image = 'series' . DS . 'posters' . DS . '0.png';
?>
	
	<section class="serie">
		<div class="row-fluid">
			<div class="span2">
				<?php echo $this->Html->image($image, array('alt' => 'Poster de ' . $v['title'], 'class' => 'thumbnail centre')); ?>
			</div>
			<div class="span10">
				<div class="page-header">
					<h1>
						<?php echo $v['title']; ?>
						<div class="pull-right">
							<?php echo $this->Html->link(
								'<i class="icon-eye-open"></i> Afficher cette série',
								array('controller' => 'series', 'action' => 'serie', $v['id']),
								array('class' => 'btn')); ?>
						</div>
					</h1>
				</div>
				<p>
					<div class="progress progress-info progress-striped active">
						<div class="bar" style="width: <?php echo $v['progression'] ?>%;"></div>
						<div class="text"><?php echo $v['progression'] ?> %</div>
					</div>
				</p>
				<div class="row-fluid">
					<div class="span6">
						<h3>Synopsis</h3>
						<p class="justify">
							<?php echo $this->Text->split($v['synopsis'], 255); ?>
						</p>
					</div>
					<div class="span3 centre">
						<h3>Nombre de saisons</h3>
						<div class="nb"><?php echo $v['nbseasons']; ?></div>
					</div>
					<div class="span3 centre">
						<h3>Nombre d'épisodes</h3>
						<div class="nb"><?php echo $v['nbepisodes']; ?></div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php endforeach ?>