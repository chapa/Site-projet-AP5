<?php

class SeasonsController extends AppController
{
	public function season($season_id=0,$user_id=0)
	{
		
		if(!empty($_SESSION))
		{
			$info_episodes=$this->Season->find(array(
				'fields' => '*',
				'tables' => 'episodes',
				'conditions' => array('season_id' => (int) $season_id),
				'order'=> 'num'
				));
			if(!empty($info_episodes))//la serie existe
			{


				
				


				if(empty($user_id))
				{
					$user_id=$_SESSION['user']['id'];
				}
				$d = $this->Season->findFirst(array(
					'fields' => 'username',
					'tables' => 'users',
					'conditions' => array('id' => (int) $user_id)
					));
				
				$this->set('username', $d['username']);
				
				
				

				
				//cherche la progression de la serie (0 ou 100)
				$serie_id=$this->Season->findFirst(array(
					'fields' => 'Serie_id,num,yearstart,yearend,mark',
					'tables' => 'Seasons',
					'conditions' => array('id' => (int)$season_id)
					));

				$serie= $this->Season->findFirst(array(

						'tables' =>	'series',
						'conditions' => array('id' => $serie_id['serie_id'])
					));
				
				$serie['actors'] = explode(', ', $serie['actors']);
				$progression=$this->Season->findFirst(array(
					'fields' => 'progression',
					'tables' => 'seriesWatched',
					'conditions' => array('user_id' => (int)$user_id,
										'serie_id'=> $serie_id['serie_id'])
					));


				
				$serie['numseason']=$serie_id['num'];
				$d = $this->Season->findFirst(array(
					'fields' => 'progression',
					'tables' => 'SeriesWatched',
					'conditions' => array('user_id' => (int)$user_id, 'serie_id' => $serie_id['serie_id'])
				));
				$serie['progression'] = !empty($d) ? current($d) : 0;

				if(empty($progression['progression']))
				{
					$progression['progression']=0;
				}
				if($progression['progression']==100|| $progression['progression']==0)
				{	
					$seasonWatched=0;
					$nbSeason=0;

					$this->set(array('progression' => $progression['progression']));

					foreach ($info_episodes as $key => $value) {
						$seasonWatched+=$progression['progression']/100;
						$nbSeason++;
						$info_episodes[$key]['vue']=$progression['progression']/100;
					}
					$seasonNotWatched=$nbSeason-$seasonWatched;
				}

				else//la progression de la serie ne nous permet pas de conaitre la progression des episodes
				{
					unset($progression);
					//cherche la progression de la season (0 ou 100)
					$progression=$this->Season->findFirst(array(
						'fields' => 'progression',
						'tables' => 'SeasonsWatched',
						'conditions' => array('user_id' => (int)$user_id,
							'season_id'=> $season_id)
						));
					
					if(empty($progression['progression']))
					{
						$progression['progression']=0;
					}

					$this->set(array('progression' => $progression['progression']));
					if($progression['progression']==100|| $progression['progression']==0)
					{
						$seasonWatched=0;
						$nbSeason=0;
						foreach ($info_episodes as $key => $value) {
							$seasonWatched+=$progression['progression']/100;
							$nbSeason++;
							$info_episodes[$key]['vue']=$progression['progression']/100;
							
						}
						$seasonNotWatched=$nbSeason-$seasonWatched;
						
					}
					else//la progression de la saison ne nous permet pas de savoir la progression des episodes
					{
						$seasonWatched=0;
						$nbSeason=0;
						foreach ($info_episodes as $key => $value) {
							
							$d=$this->Season->findFirst(array(
								'fields' => 'count(*) vue',
								'tables' => 'episodesWatched',
								'conditions' => array('user_id' => (int)$user_id,
									'episode_id'=> $value['id'])
								));
							$info_episodes[$key]['vue']=$d['vue'];
							$seasonWatched+=$d['vue'];
							$nbSeason++;
							
						}
						$seasonNotWatched=$nbSeason-$seasonWatched;

					}
				}

			}
			
			else
			{
				$this->Session->setFlash('La saison n\'existe pas', 'error');
				$this->redirect(array('controller' => 'series', 'action' => 'liste', $user_id),404);
			}

			$this->set(array('episodes' => $info_episodes,
				'user_id' => $user_id,
				'serie' => $serie,
				'serie_id' => $serie_id['serie_id'],
				'season' => $serie_id,
				'seasonsNotWatched'=> $seasonNotWatched,
				'seasonsWatched' => $seasonWatched

				));
			//debug($info_episodes);
		}
		
		else
		{
			$this->Session->setFlash('Vous devez être connecté pour pouvoir accéder à cette partie du site', 'error');
			$this->redirect(array('controller' => 'users', 'action' => 'login'), 403);
		}
	}
}

