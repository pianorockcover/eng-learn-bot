<?php
namespace parsers;

include 'simple_html_dom.php';

use parsers\Helpers;

class EventsHeads
{
	public static function update($from = 0, $limit = 10)
	{
		date_default_timezone_set('Europe/Moscow');

		$events = \QB::table('football_bot_event')
						->get();

		echo "Events: ".count($events)." From: {$from} Limit: {$limit}";

		$updateData = [];

		$counter = 0;
		$limitCounter = 0;

		foreach ($events as $event) {
			if ($counter < $from) {
				$counter++;

				continue;
			}

			$htmlStr = file_get_contents("http://ru.soccerway.com/{$event->head_to_head_link}");
			// $htmlStr = file_get_contents("http://ru.soccerway.com/matches/2017/10/28/england/premier-league/manchester-united-fc/tottenham-hotspur-football-club/2462891/");
			
			$html = str_get_html($htmlStr);
			$teams = $html->find('.block_h2h_matches-wrapper')[0];
			/* Очные встречи */
			$h2hMatches = static::getMatches($teams);
			// Helpers::exportVar($h2hMatches);
			// exit();

			/* Статистика по голам */
			$teams = $html->find('.block_h2h_general_statistics-wrapper')[0];
			$ballsStatistics = static::getStatistics($teams);

			/* Время забитых мячей */
			$teams = $html->find('.block_h2h_scoring_minutes-wrapper')[0];
			$minutes = static::getMinutes($teams, $htmlStr);

			/* Травмированные */
			$teams = $html->find('.block_match_sidelined-wrapper')[0];
			$injured = static::getInjured($teams); 

			$updateData = [
				'head_to_head_table' => json_encode($h2hMatches, JSON_UNESCAPED_UNICODE),
				'balls_statistics_table' => json_encode($ballsStatistics, JSON_UNESCAPED_UNICODE),
				'minutes_table' => json_encode($minutes, JSON_UNESCAPED_UNICODE),
				'injured_table' =>  json_encode($injured, JSON_UNESCAPED_UNICODE),
			];

			\QB::table('football_bot_event')
						->where('event_id', $event->event_id)
						->update($updateData);

			$limitCounter++;

			if ($limitCounter > $limit) {
				break;
			}
		}
	}

	private static function getMatches($team) 
	{
		$tableData = [];

		$i = 1;
		
		if (is_null($team)) {
			return [];
		}

		foreach ($team->find('.match') as $match) {
			$matchDate = Helpers::clearString($match->find('.timestamp')[1]->attr['data-value']);
			$matchDate = (new \DateTime())->setTimestamp($matchDate);

			$scoreOrTime = $match->find('.score-time a')[0]->plaintext;
			if (strpos($scoreOrTime, ':') !== false) {
				continue;
			}

			$score = Helpers::clearString($match->find('.score-time a')[0]->plaintext);
			$score = str_replace('Д', '', $score);
			$team_1 = $match->find('.team-a a')[0]->title;
			$team_2 = $match->find('.team-b a')[0]->title;

			$winnerTeam = Helpers::clearString($match->find('.strong a')[0]->title);

			if ($winnerTeam == $team_1) {
				$winner = 'team_1';
			} else if ($winnerTeam == $team_2) {
				$winner = 'team_2';
			} else {
				$winner = 'draw';
			}

			$tableData[] = [
				'date' => $matchDate->format('d/m/Y'),
				'team_1' => $team_1,
				'team_2' => $team_2,
				'winner' => $winner,
				'scope' => $score,
				'competition' => Helpers::clearString($match->find('.competition a')[0]->plaintext),
			];

			// Helpers::exportVar($tableData);

			$i++;

			if ($i > 5) {
				break;
			}
		}

		return $tableData;
	}

	private static function getStatistics($teams)
	{
		$tableData = [];

		if (is_null($teams)) {
			return [];
		}

		foreach ($teams->find('tr') as $row) {
			$rowData = [];
			$columns = $row->find('td');
			$rowData = [
				'team_a' => [
					'total' => Helpers::clearString($columns[0]->plaintext),
					'home' => Helpers::clearString($columns[1]->plaintext),
					'away' => Helpers::clearString($columns[2]->plaintext),
				],
				'team_b' => [
					'total' => Helpers::clearString($columns[3]->plaintext),
					'home' => Helpers::clearString($columns[4]->plaintext),
					'away' => Helpers::clearString($columns[5]->plaintext),
				],
			];

			if (Helpers::clearString($row->find('th')[0]->plaintext) == 'Забитые мячи') {
				$tableData['balls'] = $rowData;
			}

			if (Helpers::clearString($row->find('th')[0]->plaintext) == 'Пропущенные мячи') {
				$tableData['lostBalls'] = $rowData;
			}

			if (Helpers::clearString($row->find('th')[0]->plaintext) == 'Ср. кол-во забитых мячей за матч') {
				$tableData['middleBalls'] = $rowData;
			}

			if (Helpers::clearString($row->find('th')[0]->plaintext) == 'Ср. кол-во пропущенных мячей за матч') {
				$tableData['middleLostBalls'] = $rowData;
			}

			
		}

		return $tableData;
	}

	private static function getMinutes($teams, $htmlStr) 
	{
		if (is_null($teams)) {
			return [];
		}

		$tableData = [];
		foreach ($teams->find('.legend p') as $legend) {
			$tableData[] = [
				'legend' => Helpers::clearString($legend->plaintext),
			];
		}

		$script = $teams->plaintext;

		$precents = [];
		preg_match_all('#data_a.setValue(.+?);#is', $htmlStr, $precents);

		$precentsTeamA = [];

		foreach ($precents[0] as $value) {
			$value = explode(',', $value);

			if (strpos($value[2], '%') === false) {
				continue;
			}

			$value = str_replace(['\'', ')', '%', ';'], '', $value[2]);
			$precentsTeamA[] = $value;
		}

		$precents = [];
		preg_match_all('#data_b.setValue(.+?);#is', $htmlStr, $precents);

		$precentsTeamB = [];

		foreach ($precents[0] as $value) {
			$value = explode(',', $value);

			if (strpos($value[2], '%') === false) {
				continue;
			}

			$value = str_replace(['\'', ')', '%', ';'], '', $value[2]);
			$precentsTeamB[] = $value;
		}

		$i = 0;
		foreach ($tableData as $key => $data) {
			$tableData[$key]['team_a'] = $precentsTeamA[$i];
			$tableData[$key]['team_b'] = $precentsTeamB[$i];

			$i++;
		}

		return $tableData;
	}

	private static function getInjured($teams) {
		if (is_null($teams)) {
			return [];
		}

		$tableData = [];

		foreach ($teams->find('.left .player') as $row) {
			$tableData[] = [
				'team_a' => $row->plaintext,
				'team_b' => null,
			];
		}

		foreach ($teams->find('.right .player') as $key => $row) {
			if (isset($tableData[$key])) {
				$tableData[$key]['team_b'] = $row->plaintext;
			} else {
				$tableData[$key] = [];
				$tableData[$key]['team_b'] = $row->plaintext;
			}
		}

		return $tableData;
	}
}