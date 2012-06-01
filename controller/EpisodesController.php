<?php

class EpisodesController extends AppController
{
	public function watched($id=0,$value=0)
	{
		if(!empty($_SESSION['user']))
		{
			

			$user_id = $_SESSION['user']['id'];

			$season_id= $this->Episode->findFirst(array(
				'fields'=> 'season_id',
				'tables' => 'Episodes',
				'conditions' => array( 'id' =>(int) $id)
			));
			
			$serie_id= $this->Episode->findFirst(array(
				'fields'=> 'serie_id',
				'tables' => 'seasons',
				'conditions' => array( 'id' => $season_id['season_id'])
			));
			$d = $this->Episode->findFirst(array(
				'tables' => 'Watch',
				'conditions' => array('user_id' => $user_id, 'serie_id' => $serie_id['serie_id'])
			));
			if(empty($d))
			{
				$this->Session->setFlash('Vous ne suivez pas cette saison', 'error');
				$this->redirect(array('controller' => 'series', 'action' => 'liste'), 404);
			}
			if ($action==1)// episode non vu ->  vu
			{
				$this->Episode->table='EpisodesWatched';
				$this->Episode->save(array(
					'user_id'=>$user_id,
					'episode_id'=>$id));
				$this->Session->setFlash('La série a bien été marquée comme vue');
			}
			else if($action==0 )
			{
				$this->Episode->table='EpisodesWatched';
				$this->Episode->delete(array(
					'user_id'=>$user_id,
					'episode_id'=>$id));
				$this->Session->setFlash('La série a bien été marquée comme non vue');
			}

			

			
			$this->redirect(array('action' => 'serie', $id));
		}
		else
		{
			$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
			$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
		}
	}
}
