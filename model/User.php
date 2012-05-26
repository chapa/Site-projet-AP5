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
			
			return empty($this->errors);
		}
	}
