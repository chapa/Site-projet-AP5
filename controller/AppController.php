<?php

	class AppController extends Controller
	{
		var $helpers = array('Html');
		var $components = array('Session');

		public function beforeFilter()
		{
			$this->set(array('title_for_layout' => 'Series'));
		}
	}