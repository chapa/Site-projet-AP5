<?php

	class AppController extends Controller
	{
		public function beforeFilter()
		{
			$this->set(array('title_for_layout' => 'Series'));
		}
	}