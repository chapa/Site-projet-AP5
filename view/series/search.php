<?php $title_for_layout = 'Rechercher une série'; ?>

<div class="hero-unit">
	<h1><?php echo $title_for_layout; ?></h1>
	<p>Entrez un nom de série et choisissez celle qui correspond à votre recherche</p>
</div>

<?php if(empty($this->request->data['search'])) : ?>

	<div class="row-fluid">
		<div class="span3"></div>
		<div class="span6 offset3">
			<form class="form-inline centre" method="get">
				<input type="text" placeholder="Votre série" class="searchField" name="search">
				<button type="submit" class="btn" data-loading-text="Recherche en cours...">Rechercher</button>
			</form>
		</div>
	</div>

<?php else:

	if(!empty($series))
	{
		foreach($series as $code => $s)
		{
			if($s['poster'] == NULL) {
				$poster = $this->Html->image('series/posters/0.png', array('class' => 'thumbnail'));
			} else {
				$poster = $this->Html->image($s['poster'], array('class' => 'thumbnail'));
			} ?>
			<section class="serie">
				<div class="page-header">
					<div class="row-fluid">
						<div class="span9">
							<h1><?php echo $s['title']; ?></h1>
						</div>
						<div class="span3 right">
							<?php echo $this->Html->link(
								'<i class="icon-ok icon-white"></i> Valider cette série&nbsp;',
								array('controller' => 'series', 'action' => 'add', $code),
								array('class' => 'btn btn-success', 'data-loading-text' => 'Enregistrement en cours...')); ?>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span2">
						<?php echo $poster; ?>
					</div>
					<div class="span10">
						<dl>
							<?php if(!empty($s['creators'])) : ?>
								<dt>Créateurs</dt>
									<dd><p><?php echo $s['creators']; ?></p></dd>
							<?php endif;
							if(!empty($s['actors'])) : ?>
								<dt>Acteurs</dt>
									<dd><p><?php echo $s['actors']; ?></p></dd>
							<?php endif;
							if(!empty($s['dateCreation'])) : ?>
								<dt>Date de création</dt>
									<dd><p><?php echo $s['dateCreation']; ?></p></dd>
							<?php endif;
							if(!empty($s['dateArret'])) : ?>
							<dt>Date d'arrêt</dt>
								<dd><p><?php echo $s['dateArret']; ?></p></dd>
							<?php endif;
							if(!empty($s['mark'])) : ?>
							<dt>Note</dt>
								<dd>
									<p><div class="note jDisabled" id="<?php echo $s['mark']; ?>"></div></p>
								</dd>
							<?php endif; ?>
						</dl>
					</div>
				</div>
			</section> <?php
		} ?>
		<section>
			<div class="form-actions centre">
				<p>La série que vous recherchez n'est pas dans cette liste ?</p>
				<p>
					<a href="" class="btn ">Refaire une recherche</a>
				</p>
			</div>
		</section> <?php
	}
	else { ?>
		<div class="alert alert-error centre">
			Nous sommes désolé, la série que vous recherchez n'a pas été trouvé dans la base de données de AlloCiné
		</div>
		<div class="form-actions centre">
			<p>
				<?php echo $this->Html->link('Refaire une recherche', array('controller' => 'series', 'action' => 'search'), array('class' => 'btn')); ?>
			</p>
		</div>
		<?php
	}

endif; ?>