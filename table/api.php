<?php

class FootballDataApi
{
	public static function getCompetitions()
	{
		$json = file_get_contents('http://www.football-data.org/v1/competitions');
		$obj = json_decode($json);

		$competitions = []; 

		// $ruEngCompetitions = [
		// 	'Англия - премьер лига' => 'BL1',
		// 	'Франция - первая лига' => '',
		// 	'Германия - бундеслига' => '',
		// 	'Испания - премьер лига' => '',
		// 	'Италия - серия а' => '',
		// 	'Россия - премьер лига' => '',
		// 	'Лига чемпионов' => '',
		// 	'Лига европы' => '',
		// 	'Чемпионат мира' => '',
		// ];

		foreach ($obj as $item) {
			$competitions[] = [
				'Название чемпионата' => $item->caption,
				'Лига' => $item->league,
				'ID'=> $item->id,
			];
		}

		echo "<pre>";
		var_export($competitions);
		echo "</pre>";
	}
}

FootballDataApi::getCompetitions();