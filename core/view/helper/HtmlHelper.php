<?php

	class HtmlHelper
	{
		public function charset($name)
		{
			return '<meta charset="' . $name . '">';
		}

		public function css($name)
		{
			if(substr($name, 0, 7) == 'http://')
				$file = $name;
			elseif(substr($name, 0, 4) == 'www.')
				$file = 'http://' . $name;
			else
				$file = BASE_URL . DS . 'webroot' . DS . 'css' . DS . $name . '.css';

			return '<link rel="stylesheet" href="' . $file . '">';
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

		public function url($url, $full = false)
		{
			$out = '';
			if($full)
				$out .=  'http://' . $_SERVER['HTTP_HOST'];

			if(is_array($url))
			{
				$out .= '/' . $url['controller'] . '/' . $url['action'];
				
				foreach($url as $k=>$v)
				{
					if($k != 'controller' AND $k != 'action')
						$out .= '/' . $k . ':' . $v;
					if(empty($k))
						$out .= '/' . $v;
				}
			}
			else
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
				if($url == '/')
					$out .= BASE_URL;
				elseif(!is_array($url) AND strstr($url, 'http://'))
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
