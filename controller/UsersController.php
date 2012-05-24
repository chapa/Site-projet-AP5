<?php

	class UsersController extends AppController
	{
		public function login()
		{
			if(!empty($this->request->data))
			{
				if($this->User->validate($this->request->data, 'login'))
				{
					$d = $this->User->find(array(
						'fields' => 'id, username, status',
						'conditions' => array(
							'username' => $this->request->data['username'],
							'password' => sha1($this->request->data['password'])
						)
					));

					if(!empty($d))
					{
						$this->Session->setFlash('Redirection ... Vous avez bien été connecté ... enfin bientot :-°');
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
	}
