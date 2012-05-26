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
							'fields' => 'id, username, status',
							'conditions' => array(
								'username' => $this->request->data['username'],
								'password' => sha1($this->request->data['password'])
							)
						));

						if(!empty($d))
						{
							$_SESSION['user']['id'] = $d['id'];
							$_SESSION['user']['username'] = $d['username'];
							$_SESSION['user']['status'] = $d['status'];

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

		public function edit($id = 0)
		{
			if(!empty($_SESSION['user']))
			{
				if($id == 0)
					$id = $_SESSION['user']['id'];

				if($id != $_SESSION['user']['id'] AND $_SESSION['user']['status'] != 'Administrateur')
				{
					$this->Session->setFlash('Vous ne pouvez pas modifier le profil d\'un autre membre', 'error');
					$this->redirect(array('action' => 'edit'));
				}
				else
				{
					if(!empty($this->request->data))
					{
						if(isset($this->request->data['username']) AND $_SESSION['user']['status'] != 'Administrateur')
							unset($this->request->data['username']);
						$data['id'] = $id;
						foreach($this->request->data as $k => $v) {
							if(!empty($v) AND in_array($k, array('username', 'mail', 'newPass1', 'newPass2', 'oldPass')))
								$data[$k] = html_entity_decode(preg_replace_callback('#(%[0-9]+)#', create_function('$m', 'return "&#".hexdec($m[0]).";";'), $v));
						}
						
						if($this->User->validate($data, 'edit'))
						{
							$this->User->save($data);
							$this->Session->setFlash('Vos modifications ont bien étés enregistrées');
							$this->redirect();
						}
						else
						{
							$this->Session->setFlash('Erreur lors de la saisie des champs', 'error');
							$this->request->data = $data;
							$this->request->data += $this->User->findFirst(array(
								'conditions' => array('id' => $id)
							));
						}
					}
					else
					{
						$this->request->data = $this->User->findFirst(array(
							'conditions' => array('id' => $id)
						));
					}
				}
			}
			else
			{
				/* FAIRE PEUT ETRE UNE PAGE 403 IÇI, POUR DIRE QU'ON A PAS LE DROIT D'Y ALLER */
				$this->Session->setFlash('Vous ne pouvez pas accéder à cette page car vous n\'êtes pas connecté', 'error');
				$this->redirect(array('action' => 'login'));
			}
		}
	}
