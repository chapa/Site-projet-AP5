<?php

	class Controller
	{
		public $request;				// Objet request envoyé par le dispatcher (contenant l'url parsé)
		public $vars = array();			// Contient les variables à extraire pour la vue
		public $layout = 'default';		// Nom du layout par défaut
		public $rendered = false;		// Indique si la vue a déjà été rendu
		public $helpers = array();		// Tableau des helpers à charger
		public $components = array();	// Tableau des components à charger

		public function __construct($request)
		{
			$this->request = $request;
		}

		public function loadModel($name = NULL)
		{
			if(empty($name))
			{
				$exceptions = array('News');
				$name = ucfirst($this->request->controller);
				if(!in_array($name, $exceptions))
					$name = preg_replace('#s$#', '', $name);
			}

			$file = ROOT . DS . 'model' . DS . $name . '.php';
			if(!is_file($file))
			{
				die('Le model <strong>' . $name . '</strong> n\'existe pas');
			}
			else
			{
				if(is_file(ROOT . DS . 'model' . DS . 'AppModel.php'))
					require_once(ROOT . DS . 'model' . DS . 'AppModel.php');
				else
					require_once(CORE . DS . 'model' . DS . 'AppModel.php');

				require_once($file);
				$this->$name = new $name;
			}
		}

		public function loadComponents()
		{
			foreach($this->components as $component)
			{
				if(empty($this->$component))
				{
					$name = ucfirst($component);
					$file = ROOT . DS . 'core' . DS . 'controller' . DS . 'component' . DS . $name . 'Component.php';

					if(!is_file($file))
					{
						die('Le component <strong>' . $name . '</strong> n\'existe pas');
					}
					else
					{
						require_once($file);
						$toLoad = $name . 'Component';
						$this->$name = new $toLoad;
					}
				}
			}
		}

		public function loadHelpers()
		{
			foreach($this->helpers as $helper)
			{
				if(empty($this->$helper))
				{
					$name = ucfirst($helper);
					$file = ROOT . DS . 'core' . DS . 'view' . DS . 'helper' . DS . $name . 'Helper.php';

					if(!is_file($file))
					{
						die('Le helper <strong>' . $name . '</strong> n\'existe pas');
					}
					else
					{
						require_once($file);
						$toLoad = $name . 'Helper';
						$this->$name = new $toLoad;
					}
				}
			}
		}

		public function set($key, $value = NULL)
		{
			if(is_array($key)) {
				foreach ($key as $k => $v) {
					$this->vars[$k] = $v;
				}
			}
			elseif(!empty($value)) {
				$this->vars[$key] = $value;
			}
			else {
				return false;
			}
		}

		/**
		* Affiche la vue si elle n'a pas déjà été rendue (@var $this->rendered)
		**/
		public function render($view = NULL)
		{
			if($this->rendered)
				return false;

			if(empty($view))
				$view = $this->request->action;

			extract($this->vars);
			unset($this->vars);

			if(strpos($view, '/') === 0)
				$viewFile = ROOT . DS . 'view' . DS . $view . '.php';
			else
				$viewFile = ROOT . DS . 'view' . DS . $this->request->controller . DS . $view . '.php';

			if(!is_file($viewFile))
			{
				$this->error('404', 'La vue <strong>' . $view . '</strong> n\'existe pas');
				return false;
			}
			else
			{
				ob_start();
				require_once($viewFile);
				$content_for_layout = ob_get_clean();

				$layoutFile = ROOT . DS . 'view' . DS . 'layout' . DS . $this->layout . '.php';

				if(!is_file($layoutFile))
				{
					die('Le layout <strong>' . $this->layout . '</strong> n\'existe pas');
				}
				else
				{
					require_once($layoutFile);
					$this->rendered = true;
				}
			}
		}

		public function error($code = '200', $message)
		{
			$status = array(
				'200' => '200 OK',
				'301' => '301 Moved Permanently',
				'403' => '403 Forbidden',
				'404' => '404 Not Found'
			);
			header('HTTP/1.1 ' . $status[$code]);
			if(is_file(ROOT . DS . 'view' . DS . 'layout' . DS . 'error.php'))
				$this->layout = 'error';
			$this->set(array(
				'message' => $message,
				'title_for_layout' => 'Erreur ' . $code
			));
			$this->render('/errors/' . $code);
		}
	}
