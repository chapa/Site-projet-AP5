<?php

class SeasonsController extends AppController
{
	public function season($season_id=0,$user_id=0)
	{
		
		if(!empty($_SESSION))
		{