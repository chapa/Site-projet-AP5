<?php

	class FormHelper
	{
		public $controller;
		public $model;

		public function __construct()
		{
			$backtrace = debug_backtrace();
			$this->controller = $backtrace[1]['object'];
			$this->model = $this->controller->{$this->controller->model};
		}

		public function create($action = NULL, $method = 'get')
		{
			if($action)
				echo '<form action="' . $this->controller->Html->url($action) . '" method="' . $method . '" class="form-horizontal">';
			else
				echo '<form method="' . $method . '" class="form-horizontal">';
		}

		public function input($label, $name, $options = array())
		{
			if(!empty($this->model->errors[$name]))
			{
				$error = $this->model->errors[$name];
				echo '<div class="control-group error">';
			}
			else {
				echo '<div class="control-group">';
			}

			if(isset($options['value']))
				$value = $options['value'];
			else if(empty($this->controller->request->data[$name]))
				$value = '';
			else
				$value = $this->controller->request->data[$name];

			echo '<label class="control-label" for="input' . $name . '">' . $label . '</label>
				  <div class="controls">';

			if(!empty($options['prepend']) OR !empty($options['append']))
			{
				echo '<div class="';
					echo !empty($options['prepend']) ? 'input-prepend' : '';
					echo !empty($options['append']) ? ' input-append' : '';
				echo '">';
				echo !empty($options['prepend']) ? '<span class="add-on">' . $options['prepend'] . '</span>' : '';
			}

			if(empty($options['type']))
				$options['type'] = 'text';

			$attr = '';
			foreach($options as $k => $v)
			{
				if($k != 'type' AND $k != 'id' AND $k != 'name' AND $k != 'value')
					$attr .= ' ' . $k . '="' . $v . '"';
			}

			switch($options['type'])
			{
				case 'text':
					echo '<input type="text" id="input' . $name . '" name="' . $name . '" value="' . $value . '"' . $attr . '>';
					break;
				case 'password':
					echo '<input type="password" id="input' . $name . '" name="' . $name . '" value="' . $value . '"' . $attr . '>';
					break;
				case 'textarea':
					echo '<textarea id="input' . $name . '" name="' . $name . '"' . $attr . '>' . $value . '</textarea>';
					break;
				case 'checkbox':
					echo '<input type="hidden" name="' . $name . '" value="0"><input type="checkbox" name ="' . $name . '" value="1">';
					break;
			}

			if(!empty($options['prepend']) OR !empty($options['append']))
			{
				echo !empty($options['append']) ? '<span class="add-on">' . $options['append'] . '</span>' : '';
				echo '</div>';
			}

			if(!empty($error))
				echo '<p class="help-inline">' . $error . '</p>';

			echo '</div></div>';
		}

		public function end($submit = 'Envoyer')
		{
			echo '<div class="form-actions"><button type="submit" class="btn btn-primary">' . $submit . '</button></div>';
			echo '</form>';
		}
	}
