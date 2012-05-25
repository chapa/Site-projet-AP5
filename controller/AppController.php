<?php

	class AppController extends Controller
	{
		var $helpers = array('Html', 'Form');
		var $components = array('Session');

		public function beforeFilter()
		{
			if(!empty($_SESSION['user']))
			{
				switch ($_SESSION['user']['status']) {
					case 'Membre':
						$this->layout = 'membre';
						break;
					case 'Administrateur':
						$this->layout = 'admin';
						break;
				}
			}
			$this->set(array('title_for_layout' => 'Series'));
		}
	}