<?php

	class User extends AppModel
	{
		public function validate($data, $action)
		{
			if($action == 'login')
			{
				if(empty($data['username']))
					$this->errors['username'] = 'Votre pseudo ne doit pas être vide';

				if(empty($data['password']))
					$this->errors['password'] = 'Votre mot de passe ne doit pas être vide';
			}
			
			return empty($this->errors);
		}
	}
