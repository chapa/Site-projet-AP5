<?php

	class AppController extends Controller
	{
		var $helpers = array('Html');

		public function beforeFilter()
		{
			$this->set(array('title_for_layout' => 'Series'));
		}
	}