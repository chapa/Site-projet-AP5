<?php

	class AppController extends Controller
	{
		var $helpers = array('Html', 'Form');
		var $components = array('Session');

		public function beforeFilter()
		{
			$this->set(array('title_for_layout' => 'Series'));
		}
	}