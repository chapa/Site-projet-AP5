<?php

	class HtmlHelper
	{
		public $controller;

		public function __construct()
		{
			$backtrace = debug_backtrace();
			$this->controller = $backtrace[1]['object'];
		}

		public function charset($name = 'utf-8')
		{
			return '<meta http-equiv="Content-Type" content="text/html; charset=' . $name . '">';
		}

		public function css($name)
		{
			if(substr($name, 0, 7) == 'http://')
				$file = $name;
			elseif(substr($name, 0, 4) == 'www.')
				$file = 'http://' . $name;
			else
				$file = BASE_URL . DS . 'webroot' . DS . 'css' . DS . $name . '.css';

			return '<link rel="stylesheet" type="text/css" href="' . $file . '">';
		}

		public function js($name)
		{
			if(substr($name, 0, 7) == 'http://')
				$file = $name;
			elseif(substr($name, 0, 4) == 'www.')
				$file = 'http://' . $name;
			else
				$file = BASE_URL . DS . 'webroot' . DS . 'js' . DS . $name . '.js';
			
			return '<script src="' . $file . '"></script>';
		}

		public function image($name, $attributes = array('alt' => ''))
		{
			if(substr($name, 0, 7) == 'http://')
				$file = $name;
			elseif(substr($name, 0, 4) == 'www.')
				$file = 'http://' . $name;
			else
				$file = BASE_URL . DS . 'webroot' . DS . 'img' . DS . $name;
			
			$out = '<img src="' . $file . '"';
			foreach ($attributes as $k => $v)
			{
				$out .= ' ' . $k . '="' . $v . '"';
			}
			$out .= '>';

			return $out;
		}

		public function url($url = array(), $full = false)
		{
			$out = '';
			if($full)
				$out .=  'http://' . $_SERVER['HTTP_HOST'];

			$out .= BASE_URL;

			if(is_array($url))
			{
				if(empty($url['controller']))
					$url['controller'] = $this->controller->request->controller;
				if(empty($url['action']))
					$url['action'] = $this->controller->request->action;

				$out .= '/' . $url['controller'] . '/' . $url['action'];
				
				foreach($url as $k=>$v)
				{
					if($k != 'controller' AND $k != 'action')
						$out .= '/' . $k . ':' . $v;
					if(empty($k))
						$out .= '/' . $v;
				}
			}
			else if($url != '/')
			{
				$out = $url;
			}

			return $out;
		}

		public function link($text, $url = null, $attributes = array())
		{
			$out = '<a href="';
			
			if(!empty($url))
			{
				if(!is_array($url) AND strstr($url, 'http://'))
					$out .= $url;
				else
					$out .= $this->url($url, false);
			}
			else
				$out .= '#';
			
			$out .= '"';

			if(!empty($attributes) AND is_array($attributes))
			{
				foreach($attributes as $k=>$v)
				{
					$out .= ' ' . $k . '="' . $v . '"';
				}
			}

			$out .= '>' . $text . '</a>';

			return $out;
		}

		public function pixum($largeur, $hauteur, $type = '')
		{
			return '<img src="http://lorempixel.com/' . $largeur . '/' . $hauteur . '/' . $type . '" alt="Lorem pixum">';
		}
	}
