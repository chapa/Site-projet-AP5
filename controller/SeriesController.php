<?php

	class SeriesController extends AppController
	{
		public function search()
		{
			if(!empty($_SESSION['user']))
			{
				$this->loadComponent('AlloSearch');

				if(!empty($this->request->data['search']))
				{
					$this->AlloSearch->load('http://api.allocine.fr/rest/v3/search?partner=YW5kcm9pZC12M3M&q=' . $this->request->data['search'] . '&filter=tvseries');
					$this->set('series', $this->AlloSearch->getInfos());
				}
			}
			else
			{
				$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
				$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
			}
		}

		public function add($id)
		{
			if(!empty($_SESSION['user']))
			{
				$d = $this->Serie->find(array(
					'fields' => 'id ',
					'tables' => 'series',
					'conditions' => array('id' => $id)
				));
				
				if (!empty($d))
				{
					$d=$this->Serie->find(array(
						'fields' => 'user_id ',
						'tables' => 'watch',
						'conditions' => array('user_id' => $_SESSION['user']['id'], 'serie_id'=> $id)
					));

					if (empty($d))
					{
						$this->Serie->table = 'Watch';
						$this->Serie->save(array(
							'user_id'=> $_SESSION['user']['id'],
							'serie_id'=> $id
						));
						$this->Session->setFlash('La série a bien été enregistrée');
						$this->redirect(array('action' => 'serie', $id), 200);
					}
					else
					{
						$this->Session->setFlash('Vous régardez déjà cette série', 'warning');
						$this->redirect(array('action' => 'serie', $id), 200);
					}
				}
				else
				{
					$this->Session->setFlash('Désolé mais la série n\'est pas dans la base');
					$this->redirect(array('action' => 'search'), 200);
				}

			}
			else
			{
				$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
				$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
			}
		}
		
		public function liste($id = 0)
		{
			if(!empty($_SESSION['user']))
			{
				if(empty($id))
					$id = $_SESSION['user']['id'];

				$d = $this->Serie->findFirst(array(
					'fields' => 'username',
					'tables' => 'users',
					'conditions' => array('id' => (int) $id)
				));
				$this->set('username', $d['username']);

				if(empty($d))
				{
					$this->Session->setFlash('L\'utilisateur n\'existe pas', 'error');
					$this->redirect(array('controller' => 'users', 'action' => 'liste'), 404);
				}

				$d = $this->Serie->find(array(
					'fields' => 'serie_id',
					'tables' => 'watch',
					'conditions' => array('user_id' => $id)
				));

				$series = array();
				foreach($d as $k => $v)
				{
					$series[$k] = $this->Serie->findFirst(array(
						'fields' => 'id, title, synopsis, nbseasons, nbepisodes',
						'conditions' => array('id' => $v['serie_id'])
					));
					$series[$k]['progression'] = $this->Serie->findFirst(array(
						'fields' => 'progression',
						'tables' => 'seriesWatched',
						'conditions' => array('user_id' => $id, 'serie_id' => $v['serie_id'])
					));
					$series[$k]['progression'] = empty($series[$k]['progression']) ? 0 : current($series[$k]['progression']);
				}

				$this->loadHelper('Text');
				$this->set(array(
					'series' => $series,
					'user_id' => $id
				));
			}
			else
			{
				$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
				$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
			}
		}

		public function serie($id = 0, $user_id = 0)
		{
			if(!empty($_SESSION['user']))
			{
				if(empty($user_id))
					$user_id = $_SESSION['user']['id'];

				$d = $this->Serie->findFirst(array(
					'fields' => 'username',
					'tables' => 'users',
					'conditions' => array('id' => (int) $user_id)
				));
				$this->set('username', $d['username']);
				if(empty($d))
				{
					$this->Session->setFlash('L\'utilisateur n\'existe pas', 'error');
					$this->redirect(array('controller' => 'users', 'action' => 'liste'), 404);
				}

				$d = $this->Serie->findFirst(array(
					'tables' => 'Watch',
					'conditions' => array('user_id' => $user_id, 'serie_id' => $id)
				));
				if(empty($d))
				{
					$this->Session->setFlash('L\'utilisateur ne suit pas cette série', 'error');
					$this->redirect(array('controller' => 'series', 'action' => 'liste', $user_id), 404);
				}

				$serie = $this->Serie->findFirst(array(
					'conditions' => array('id' => $id)
				));

				$serie['actors'] = explode(', ', $serie['actors']);
				$d = $this->Serie->findFirst(array(
					'fields' => 'progression',
					'tables' => 'SeriesWatched',
					'conditions' => array('user_id' => $user_id, 'serie_id' => $id)
				));
				$serie['progression'] = !empty($d) ? current($d) : 0;

				$seasons = $this->Serie->find(array(
					'tables' => 'seasons',
					'conditions' => array('serie_id' => $id),
					'order' => 'num'
				));

				$seasonsNotWatched = $seasonsWatched = 0;
				foreach ($seasons as $k => $v)
				{
					$d = $this->Serie->findFirst(array(
						'fields' => 'progression',
						'tables' => 'SeasonsWatched',
						'conditions' => array('user_id' => $user_id, 'season_id' => $v['id'])
					));
					$seasons[$k]['progression'] = !empty($d) ? current($d) : 0;
					
					if($seasons[$k]['progression'] < 100)
						$seasonsNotWatched ++;
					else
						$seasonsWatched ++;
				}

				$this->set(array(
					'serie' => $serie,
					'seasons' => $seasons,
					'seasonsNotWatched' => $seasonsNotWatched,
					'seasonsWatched' => $seasonsWatched,
					'user_id' => $user_id
				));
			}
			else
			{
				$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
				$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
			}
		}

		public function delete($id)
		{
			if(!empty($_SESSION['user']))
			{
				$user_id = $_SESSION['user']['id'];

				$d = $this->Serie->findFirst(array(
					'tables' => 'Watch',
					'conditions' => array('user_id' => $user_id, 'serie_id' => $id)
				));
				if(empty($d))
				{
					$this->Session->setFlash('Vous ne suivez pas cette série', 'error');
					$this->redirect(array('controller' => 'series', 'action' => 'liste'), 404);
				}

				$this->Serie->table = 'Watch';
				$this->Serie->delete(array(
					'user_id' => $user_id,
					'serie_id' => $id
				));

				$this->Session->setFlash('La série a bien été supprimée');
				$this->redirect(array('action' => 'liste'), 200);
			}
			else
			{
				$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
				$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
			}
		}
	}
