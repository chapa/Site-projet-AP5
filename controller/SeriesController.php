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
				$this->set('series', $series);
			}
			else
			{
				$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
				$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
			}
		}
	}
