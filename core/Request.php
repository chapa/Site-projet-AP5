<?php

	/**
	* Classe contenant la requête de l'utilisateur (déterminée en fontion de l'url)
	**/
	class Request
	{
		public $url;				// Url demandée (sous la forme 'controller/action[/param...][/param:value...]')
		public $controller;			// Nom du controller appelé
		public $action;				// Nom de l'action appelée
		public $params = array();	// Tableau des paramètres non nommés
		public $named = array();	// Tableau des paramètres nommés ('param' => 'value')
		public $specialParams = array('page'); // Liste des paramètres nommés à placer directement en tant qu'attribut de la classe ($this->param)

		/**
		* Constructeur, appelé pour ranger les infos de l'url dans les attributs de la classe
		**/
		public function __construct($url = NULL)
		{
			$this->url = !empty($url) ? trim($url, '/') : '/';
			if(empty($this->url))
				$this->url = '/';

			$params = explode('/', $this->url);

			$this->controller = !empty($params[0]) ? $params[0] : 'pages';
			$this->action = !empty($params[1]) ? $params[1] : 'index';
			$this->params = array_slice($params, 2);

			foreach($this->params as $k => $v)
			{
				$named = explode(':', $v);
				if(!empty($named[1]))
				{
					if(in_array($named[0], $this->specialParams))
						$this->{$named[0]} = $named[1];
					else
						$this->named[$named[0]] = $named[1];
					unset($this->params[$k]);
				}
			}
		}
	}