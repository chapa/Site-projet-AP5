<?php

	class User extends AppModel
	{
		public function validate(&$data, $action)
		{
			if($action == 'login')
			{
				if(empty($data['username']))
					$this->errors['username'] = 'Votre pseudo ne doit pas être vide';

				if(empty($data['password']))
					$this->errors['password'] = 'Votre mot de passe ne doit pas être vide';
			}
			else if($action == 'edit')
			{
				if(empty($data['mail']))
					$this->errors['mail'] = 'Votre adresse email ne doit pas être vide';
				else if(!filter_var($data['mail'], FILTER_VALIDATE_EMAIL))
					$this->errors['mail'] = 'Votre adresse email n\'a pas une forme valide';

				if(isset($data['newPass1']) AND isset($data['newPass2']))
				{
					if(isset($data['oldPass']))
					{
						if($data['newPass1'] != $data['newPass2'])
						{
							$this->errors['newPass1'] = ' ';
							$this->errors['newPass2'] = 'Les mots de passes sont différents';
						}
						else
						{
							$d = $this->find(array(
								'conditions' => array(
									'id' => $data['id'],
									'password' => sha1($data['oldPass'])
								)
							));

							if(empty($d))
							{
								$this->errors['oldPass'] = 'Vous avez entré un mauvais mot de passe';
							}
							else
							{
								$data['password'] = sha1($data['newPass1']);
								unset($data['newPass1']);
								unset($data['newPass2']);
								unset($data['oldPass']);
							}
						}
					}
					else
					{
						$this->errors['oldPass'] = 'Vous devez renseigner votre ancien mot de passe pour pouvoir le modifier';
						unset($data['oldPass']);
					}
				}
				else if(isset($data['newPass1']) OR isset($data['newPass2']))
				{
					$this->errors['newPass1'] = ' ';
					$this->errors['newPass2'] = 'Les mots de passes sont différents';
				}

			}

			
			else if($action == 'signup')

			{
				$d = $this->find(array('conditions' => array('mail' => $data['mail'])));
				
				if(empty($data['mail']))
					$this->errors['mail'] = 'Votre adresse email ne doit pas être vide';
				else if(!filter_var($data['mail'], FILTER_VALIDATE_EMAIL))
				{
					$this->errors['mail'] = 'Votre adresse email n\'a pas une forme valide';
				}
				
				
				else if(!empty($d))
				{
					$this->errors['mail'] = 'Cette adresse mail est déja associée a un compte';
				}
				
				if(empty($data['password1']))
				{
					$this->errors['password1'] = 'Veuillez saisir votre mot de passe';
				}
				else if(strlen($data['password1'])<6)
					{
						$this->errors['password1'] = 'Votre mot de passe doit contenir au moins 6 caractères';
					}

				else if(isset($data['password2']))

				{
					if($data['password2']!=$data['password1'])
					{
						$this->errors['password2']= 'Les mots de passes sont différents';
						$this->errors['password2']= 'Les mots de passes sont différents';
					}
					else 
					{
						$data['password']=sha1($data['password1']);
						unset($data['password1']);
						unset($data['password2']);
				
					}


				}


				if(empty($data['username']))
				{

					$this->errors['username']= 'Entrez votre nom d\'utilisateur';
				}
				else
				{
					$d = $this->find(array('conditions' => array('username' => $data['username'])));
					if(!empty($d))
					{
						$this->errors['username']='ce nom d\'utilisateur est déja pris';


					}
				}


			}
			
			return empty($this->errors);
		}
	}
