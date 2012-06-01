<?php
	$title_for_layout=$serie['title'].' - Saison '.$serie['numseason'];
	$image = 'series' . DS . 'banners' . DS . $serie_id . '.jpg';
	if(!is_file(WEBROOT . DS . 'img' . DS . $image))
		$image = 'series' . DS . 'banners' . DS . '0.png';
?>
<div class="row-fluid">
	<div class="span3">&nbsp;</div>
	<div class="span6 offset3">
		<?php echo $this->Html->image($image, array('class' => 'thumbnail')); ?>
	</div>
</div>

<div class="page-header">
	<h1>
		<?php
			
			echo ($user_id != $_SESSION['user']['id']) ? '[' . $this->Html->link($username, array('action' => 'liste', $user_id), array('rel' => 'tooltip', 'data-original-title' => 'Afficher les saisons de ' . $username)) . '] ' : '';
			echo $serie['title'];
		?>
		<small><?php echo  'saison '.$serie['numseason'] ; ?></small>
		
	</h1>
</div>

<div class="row-fluid">
	<div class="span2">
		<h3>Informations</h3>
		<h6><i class="icon-play-circle"></i> Avancement</h6>
			<p>
				<div class="progress progress-info progress-striped active">
					<div class="bar" style="width: <?php echo $progression; ?>%;"></div>
					<div class="text"><?php echo $progression; ?> %</div>
				</div>
			</p>
		<h6><i class="icon-asterisk"></i> Titre original</h6>
			<p><?php echo $serie['originaltitle']; ?></p>
		<h6><i class="icon-heart"></i> Note</h6>
			<p><div class="note jDisabled" id="<?php echo $serie['mark']; ?>"></div></p>
		<h6><i class="icon-calendar"></i> Année de création</h6>
			<p><?php echo $serie['yearstart']; ?></p>
		<h6><i class="icon-calendar"></i> Année d'arrêt</h6>
		<?php if(!empty($serie['yearend'])) : ?>
			<p><?php echo $serie['yearend']; ?></p>
		<?php else : ?>
			<p>Encore en production</p>
		<?php endif; ?>
		<h6><i class="icon-time"></i> Format des épisodes</h6>
			<p><?php echo $serie['formattime']; ?> minutes</p>
		<?php if(!empty($serie['creators'])) : ?>
			<h6><i class="icon-user"></i> Réalisateur</h6>
				<p><?php echo $serie['creators']; ?></p>
		<?php endif; ?>
		<h6><i class="icon-adjust"></i> Genre</h6>
			<p><?php echo $serie['type'] ?></p>
		<h6><i class="icon-map-marker"></i> Nationalité</h6>
			<p><?php echo $serie['nationality']; ?></p>
	</div>
	<div class="span10">
		<div class="tabbable">
			<ul class="nav nav-tabs tabs">
				<li>
					<a href="#synopsis" data-toggle="tab"><i class="icon-home"></i> Synopsis</a>
				</li>
				<li class="active">
					<a href="#episodes" data-toggle="tab"><i class="icon-film"></i> Episodes</a>
				</li>
				<li>
					<a href="#acteurs" data-toggle="tab"><i class="icon-star"></i> Acteurs</a>
				</li>
				<?php if($user_id == $_SESSION['user']['id'] OR $_SESSION['user']['status'] == 'Administrateur') : ?>
					<li class="dropdown">
						<a href="#actions" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i> Actions <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php if($serie['progression'] == 100): ?>
								<li><a data-toggle="modal" href="#notWatched"><i class="icon-ok"></i> Marquer la saison comme non vue</a></li>
							<?php else: ?>
								<li><a data-toggle="modal" href="#watched"><i class="icon-ok"></i> Marquer la saison comme vue</a></li>
							<?php endif ?>
							<li class="divider"></li>
							<li><a data-toggle="modal" href="#deleteModal"><i class="icon-trash"></i> Supprimer cette série</a></li>
						</ul>
					</li>
				<?php endif; ?>
			</ul>
			
			<div class="tab-content">
				<div class="tab-pane" id="synopsis">
					<?php echo $serie['synopsis']; ?>
				</div>
				<div class="tab-pane active" id="episodes">
					<?php if($seasonsNotWatched + $seasonsWatched > 0) : ?>
						
						<h3>Saisons à voir</h3>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th class="minWidth"></th>
									<th>#</th>
									<th>Titre</th>
									<th>Note</th>
									<th >Etat</th>

								
								</tr>
							</thead>
							<tbody>

								<?php foreach($episodes as $v) :  ?>
									<tr>
										<td>
											<?php
												if($user_id == $_SESSION['user']['id'])
												{
													echo $this->Html->link(
														'Afficher',
														array('controller' => 'seasons', 'action' => 'season', $v['id']),
														array('class' => 'btn btn-mini')
													);
												}
												else
												{
													echo $this->Html->link(
														'Afficher',
														array('controller' => 'seasons', 'action' => 'season', $v['id'], $user_id),
														array('class' => 'btn btn-min
													);
												}
											?>
										</td>
										<td><?php echo $v['num']; ?></td>
										<td><?php echo $v['title']; ?></td>
										
										<td><div class="note jDisabled" id="<?php echo $v['mark']; ?>"></div></td>
										<td>
											<?php if ($v['vue']): ?>
												<span class="label label-success">Vu</span>
											<?php else: ?>
												<span class="label">Pas vu</span>
												
											<?php endif ?>
											
											
										</td>
									</tr>
								<?php  endforeach; ?>
							</tbody>
						</table>
						
					<?php else : ?>
						<div class="alert alert-error centre">
							Nous sommes désolé mais aucune saison n'a été trouvée dans la base de données de AlloCiné pour cette série
						</div>
					<?php endif; ?>
				</div>
				<div class="tab-pane" id="acteurs">
					<ul>
						<?php foreach($serie['actors'] as $a) : ?>
							<li><?php echo $a; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if($user_id == $_SESSION['user']['id'] OR $_SESSION['user']['status'] == 'Administrateur') : ?>
	<div class="modal hide fade" id="deleteModal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>Attention</h3>
		</div>
		<div class="modal-body">
			<p>
				Êtes-vous sûr de bien vouloir supprimer cette série ?<br>
				Toutes vos progressions seront alors supprimées.
			</p>
		</div>
		<div class="modal-footer">
			<?php echo $this->Html->link(
				'Supprimer',
				array('controller' => 'series', 'action' => 'delete', $serie['id']),
				array('class' => 'btn btn-primary')
			); ?>
			<a class="btn" data-dismiss="modal">Annuler</a>
		</div>
	</div>

	<div class="modal hide fade" id="notWatched">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>Attention</h3>
		</div>
		<div class="modal-body">
			<p>
				Êtes-vous sûr de bien vouloir marquer cette saison comme non vue ?<br>
				Tous les épisodes de toutes les saisons seront alors marqués comme non vus.
			</p>
		</div>
		<div class="modal-footer">
			<?php echo $this->Html->link(
				'Marquer la saison comme non vue',
				array('controller' => 'series', 'action' => 'watched', $serie['id'], false),
				array('class' => 'btn btn-primary')
			); ?>
			<a class="btn" data-dismiss="modal">Annuler</a>
		</div>
	</div>

	<div class="modal hide fade" id="watched">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>Attention</h3>
		</div>
		<div class="modal-body">
			<p>
				Êtes-vous sûr de bien vouloir marquer cette saison comme vue ?<br>
				Tous les épisodes de toutes les saisons seront alors marqués comme vus.
			</p>
		</div>
		<div class="modal-footer">
			<?php echo $this->Html->link(
				'Marquer la saison comme vue',
				array('controller' => 'series', 'action' => 'watched', $serie['id'], true),
				array('class' => 'btn btn-primary')
			); ?>
			<a class="btn" data-dismiss="modal">Annuler</a>
		</div>
	</div>
<?php endif; ?>
