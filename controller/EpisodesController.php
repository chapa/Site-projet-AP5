<?php

class EpisodesController extends AppController
{
	public function watched($id=0,$value=0)
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
				$this->Session->setFlash('Vous ne suivez pas cette saison', 'error');
				$this->redirect(array('controller' => 'series', 'action' => 'liste'), 404);
			}

			$d = $this->Serie->find(array(
				'fields' => 'id',
				'tables' => 'Episodes',
				'conditions' => array('serie_id' => $id)
			));
			$episodes = array();
			foreach($d as $v) {
				$e = $this->Serie->find(array(
					'fields' => 'id',
					'tables' => 'episodes',
					'conditions' => array('season_id' => $v['id'])
				));
				$episodes = array_merge($episodes, $e);
			}

			
			$this->redirect(array('action' => 'serie', $id));
		}
		else
		{
			$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
			$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
		}
	}

	public function episode($id, $user_id = 0)
	{
		if(!empty($_SESSION['user']))
		{
			if($user_id == 0)
				$user_id = $_SESSION['user']['id'];

			$d = $this->Episode->findFirst(array(
				'fields' => 'season_id',
				'conditions' => array('id' => $id)
			));

			if(empty($d))
			{
				$this->Session->setFlash('L\'épisode n\'existe pas', 'error');
				$this->redirect(array('controller' => 'series', 'action' => 'liste'));
			}
			else
			{
				$this->Session->setFlash('Cette fonctionnalité n\'est pas disponible (pas le temps)', 'error');
				$this->redirect(array('controller' => 'seasons', 'action' => 'season', $d['season_id'], $user_id));
			}
		}
		else
		{
			$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
			$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
		}
	}
}
