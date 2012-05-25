<?php

	class UsersController extends AppController
	{
		public function login()
		{
			if(empty($_SESSION['user']))
			{
				if(!empty($this->request->data))
				{
					if($this->User->validate($this->request->data, 'login'))
					{
						$d = $this->User->findFirst(array(
							'fields' => 'id, status',
							'conditions' => array(
								'username' => $this->request->data['username'],
								'password' => sha1($this->request->data['password'])
							)
						));

						if(!empty($d))
						{
							$_SESSION['user']['id'] = $d->id;
							$_SESSION['user']['status'] = $d->status;

							$this->Session->setFlash('Vous êtes mainenant connecté');
							$this->redirect('/');
						}
						else
						{
							$this->Session->setFlash('Le nom d\'utilisateur ou le mot de passe est incorrect, veuillez réessayer', 'error');
						}
					}
					else
					{
						$this->Session->setFlash('Erreur lors de la saisie des champs', 'error');
					}
				}
			}
			else
			{
				$this->Session->setFlash('Vous êtes déjà connecté', 'warning');
				$this->redirect('/');
			}
		}

		public function logout()
		{
			if(!empty($_SESSION['user']))
			{
				session_destroy();
				session_start();
				$this->Session->setFlash('Vous êtes bien déconnecté');
			}
			else
			{
				$this->Session->setFlash('Vous n\'êtes pas encore connecté', 'warning');
			}
			$this->redirect('/');
		}
	}
