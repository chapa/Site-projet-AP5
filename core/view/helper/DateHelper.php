<?php

	class DateHelper
	{
		function show($datetime, $full = true, $affJour = false, $heure = false)
		{
			$date = '';
			$tmpstamp = strtotime($datetime);
			$jour = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
			if(!$full)
			{
				$separator = '/';
				$mois = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
			}
			else
			{
				$separator = ' ';
				$mois = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
			}
			if($affJour)
				$date = $jour[date('N', $tmpstamp) - 1] . $separator;
			$date .= date('d', $tmpstamp) . $separator . $mois[date('n', $tmpstamp) - 1] . $separator . date('Y', $tmpstamp);
			if($heure == true)
				$date .= ' à ' . date('H:i:s', $tmpstamp);
			
			return $date;
		}
		
		function showShort($datetime, $heure = false)
		{
			$datetime = strtotime($datetime);
			$date = '';
			$mois = array('Janv', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Dec');
			$date .= date('d', $datetime) . '/' . $mois[date('n', $datetime) - 1] . '/' . date('Y', $datetime);
			if ($heure == true)
				$date .= ' à ' . date('H\hi', $datetime);
			
			return $date;
		}
	}
