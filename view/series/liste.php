<?php
	$title_for_layout = (isset($this->request->params[0]) AND $this->request->params[0] != $_SESSION['user']['id']) ? 'Séries de ' . $username : 'Mes séries';
	if($user_id == $_SESSION['user']['id'])
		$user_id = NULL;
?>

<div class="hero-unit">
	<h1><?php echo $title_for_layout; ?></h1>
	<?php if(isset($this->request->params[0]) AND $this->request->params[0] != $_SESSION['user']['id']) : ?>
		<p>Vous trouverez ici toutes les séries que <?php echo $username; ?> a ajouté</p>
	<?php else : ?>
		<p>Vous trouverez ici toutes les séries que vous avez ajouté</p>
	<?php endif; ?>
</div>

<?php if(empty($series)) : ?>
	<div class="alert alert-block alert-warning fade in">
		<h4 class="alert-heading">Attention</h4>
		<?php if($user_id) : ?>
			<?php echo $username; ?> ne suit encore aucune séries.
		<?php else : ?>
			Vous ne suivez encore aucune séries, cliquez sur ce lien pour <?php echo $this->Html->link('rechercher des séries', array('controller' => 'series', 'action' => 'search')); ?>.
		<?php endif; ?>
	</div>
<?php endif; ?>

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
								array('controller' => 'series', 'action' => 'serie', $v['id'], $user_id),
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