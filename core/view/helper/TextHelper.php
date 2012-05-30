<?php

	class TextHelper
	{
		public function split($text, $length, $end = '...')
		{
			if(empty($text))
				return "";
			elseif(strlen($text) < $length)
				return $text;
			elseif(preg_match("/(.{1,$length})\s./ms", $text, $match))
				return $match[1] . $end;
			else
				return substr($text, 0, $length) . $end;
		}
	}