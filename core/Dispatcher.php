<?php

	class Dispatcher
	{
		public $request; // Objet Request, contenant la requête de l'utilisateur (déterminée en fontion de l'url)

		/**
		* Constructeur, c'est lui qui appelle tout (il crée la page)
		**/
		public function __construct()
		{
			// Génération de l'objet Request
			$url = str_replace('/~bourgiem/projet/', '', $_SERVER['REQUEST_URI']);
			$this->request = new Request($url);

			// Chargement du controller
			if(!$controller = $this->loadController())
				return false;
			$action = $this->request->action;

			// Si l'action n'est pas uniquement dans le controller appelé, on ne la charge pas
			if(!in_array($action, array_diff(get_class_methods($controller), get_class_methods('Controller'))))
			{
				$error = (Conf::$debug) ? 'L\'action <strong>' . $this->request->action . '</strong> du controller <strong>' . $this->request->controller . '</strong> n\'existe pas.' : 'La page à laquelle vous tentez d\'accéder n\'existe pas.';
				$controller = new AppController($this->request);
				$controller->error(404, $error);
				return false;
			}

			$controller->loadModel();
			$controller->loadComponents();

			if(method_exists($controller, 'beforeFilter'))
				$controller->beforeFilter();
			
			// Chargement de l'action (méthode du controller)
			call_user_func_array(array($controller, $action), $this->request->params);

			$controller->loadHelpers();

			if(method_exists($controller, 'beforeRender'))
				$controller->beforeRender();
			
			// Rendu final : on affiche la vue
			$controller->render();
		}

		/**
		* Charge le controller demandé dans @var $this->request->controller
		**/
		public function loadController()
		{
			$name = ucfirst($this->request->controller) . 'Controller';
			$file = ROOT . DS . 'controller' . DS . $name . '.php';

			// S'il existe on charge /controller/AppController.php, sinon /core/controller/AppController.php
			if(is_file(ROOT . DS . 'controller' . DS . 'AppController.php'))
				require_once(ROOT . DS . 'controller' . DS . 'AppController.php');
			else
				require_once(CORE . DS . 'controller' . DS . 'AppController.php');

			if(!is_file($file))
			{
				$error = (Conf::$debug) ? 'Le controller <strong>' . $this->request->controller . '</strong> n\'existe pas.' : 'La page à laquelle vous tentez d\'accéder n\'existe pas.';
				$controller = new AppController($this->request);
				$controller->error(404, $error);
				return false;
			}
			else
			{
				require_once($file);
				return new $name($this->request);
			}
		}
	}
