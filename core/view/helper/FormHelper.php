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

		public function create($action, $method = 'post')
		{
			echo '<form action="' . $this->controller->Html->url($action) . '" method="' . $method . '">';
		}

		public function input($name, $label, $options = array())
		{
			$classError = '';
			if(!empty($this->model->errors[$name]))
			{
				$error = $this->model->errors[$name];
				$classError = ' error';
				$this->controller->request->data->{$this->controller->model} = $this->controller->request->data;
			}
			if(empty($this->controller->request->data->{$this->controller->model}->$name))
				$value = '';
			else
				$value = $this->controller->request->data->{$this->controller->model}->$name;
			$out = '<div class="clearfix' . $classError . '">
						<label for="input' . $name . '">' . $label . '</label>
						<div class="input">';
			$attr = '';
			foreach($options as $k => $v)
			{
				$attr .= $k . ' = "' . $v . '" ';
			}
			if(empty($options['type']))
			{
				$out .= '<input type="text" id="input' . $name . '" name="' . $name . '" value="' . $value . '" ' . $attr . '>';
			}
			elseif($options['type'] == 'textarea')
			{
				$out .= '<textarea id="input' . $name . '" name="' . $name . '" ' . $attr . '>' . $value . '</textarea>';
			}
			elseif($options['type'] == 'checkbox')
			{
				$out .= '<input type="hidden" name="' . $name . '" value="0"><input type="checkbox" name ="' . $name . '" value="1">';
			}
			if(!empty($error))
				$out .= '<span class="help-inline">' . $error . '</span>';
			$out .= '</div></div>';

			echo $out;
		}

		public function end($submit = 'Envoyer')
		{
			echo '<div class="actions"><input type="submit" value="' . $submit . '" class="btn primary"></div>';
			echo '</form>';
		}
	}
