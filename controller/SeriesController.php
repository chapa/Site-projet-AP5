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
					debug($this->AlloSearch->getInfos());
					$this->set('series', $this->AlloSearch->getInfos());
				}
			}
			else
			{
				$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
				$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
			}
		}
		public function add($id_serie)
		{
			

			if(!empty($_SESSION['user']))
			{
				$temp=$this->Serie->table;
				$d=$this->Serie->find(array(
						'fields' => 'id ',
						'tables' => 'series',
						'conditions' => array('id' => $id_serie)
					));
				
				
					debug($d);
				if (!empty($d))
				{
					$d=$this->Serie->find(array(
						'fields' => 'user_id ',
						'tables' => 'watch',
						'conditions' => array('user_id' => $_SESSION['user']['id'],
												'serie_id'=> $id_serie)
					));

					if (empty($d))
					{
						$this->Serie->table='Watch';
						$this->Serie->save(array(
							'user_id'=> $_SESSION['user']['id'],
							'serie_id'=> $id_serie
							));
						$this->Session->setFlash('La série a bien été enregistrer');
						$this->redirect(array('action'=>'liste'));
					}
					else
					{
						$this->Session->setFlash('Vous régardé déja cette série!');
						$this->redirect(array('action'=>'search'));
					}
				}
				else
				{
					$this->Session->setFlash('Désoler la série n\'est pas dans la base');
					$this->redirect(array('action'=>'search'));
				}

			}
			Else
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
