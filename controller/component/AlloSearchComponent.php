<?php

	class AlloSearchComponent
	{
		private $doc;
		
		public function load($file)
		{
			$this->doc = simplexml_load_file($file);
		}

		public function getInfos()
		{
			$out = array();
			foreach($this->doc->tvseries as $v)
			{
				if(!empty($v['code'])) {
					$i = current($v['code']);
				} else {
					$i = 0;
				}
				if(!empty($v->title)) {
					$out[$i]['title'] = current($v->title);
				} elseif(!empty($v->originalTitle)) {
					$out[$i]['title'] = current($v->originalTitle);
				}
				if(!empty($v->poster['href'])) {
					$out[$i]['poster'] = current($v->poster['href']);
				} else {
					$out[$i]['poster'] = NULL;
				}
				if(!empty($v->castingShort->creators)) {
					$out[$i]['creators'] = current($v->castingShort->creators);
				}
				if(!empty($v->castingShort->actors)) {
					$out[$i]['actors'] = current($v->castingShort->actors);
				}
				if(!empty($v->yearStart)) {
					$out[$i]['yearStart'] = current($v->yearStart);
				}
				if(!empty($v->yearEnd)) {
					$out[$i]['yearEnd'] = current($v->yearEnd);
				} elseif(!empty($v->yearStart)) {
					$out[$i]['yearStart'] .= ', toujours en production';
				}
				if(!empty($v->statistics->userRating)) {
					$out[$i]['mark'] = current($v->statistics->userRating);
				}
			}
			return $out;
		}
	}
