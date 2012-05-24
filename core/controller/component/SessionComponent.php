<?php

	class SessionComponent
	{
		public function __construct()
		{
			session_start();
		}

		public function read($key)
		{
			return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
		}

		public function write($vars = array())
		{
			if(!empty($vars) AND is_array($vars))
			{
				foreach ($vars as $k => $v) {
					$_SESSION[$k] = $v;
				}
			}
			else
			{
				return false;
			}
		}

		public function delete($vars)
		{
			if(!empty($vars))
			{
				$vars = (array) $vars;
				foreach($vars as $v) {
					if(isset($_SESSION[$v]))
						unset($_SESSION[$v]);
				}
			}
			else
			{
				return false;
			}
		}

		public function setFlash($message, $type = 'success')
		{
			$flashes = $this->read('flashes');
			if($flashes === false)
				$flashes = array();
			$flashes[] = array('message' => $message, 'type' => $type);

			$this->write(array('flashes' => $flashes));
		}

		public function flashes()
		{
			if(!empty($_SESSION['flashes']))
			{
				foreach($_SESSION['flashes'] as $v) {
					echo '<div class="alert alert-' . $v['type'] . ' fade in"><a href="#" class="close" data-dismiss="alert">&times;</a>' . $v['message'] . '</div>';
					$this->delete('flashes');
				}
			}
		}
	}
