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
				$this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
		}
	}
