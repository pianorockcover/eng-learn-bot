<?php
namespace parsers;

include 'simple_html_dom.php';

use parsers\Helpers;

class EventsInfo
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

			$html = file_get_html("http://ru.soccerway.com/{$event->info_link}");
			$teams = $html->find('.block_match_team_matches-wrapper');

			$team_a = $teams[0];
			$team_b = $teams[1];

			$updateData = [
				'info_tables' => json_encode([
					'team_a' => static::getMatches($team_a),
					'team_b' => static::getMatches($team_b),
				], JSON_UNESCAPED_UNICODE),

				'head_to_head_link' => $html->find('#submenu li a')[2]->href,
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
		foreach ($team->find('.match') as $match) {
			$matchDate = Helpers::clearString($match->find('.timestamp')[1]->attr['data-value']);
			$matchDate = (new \DateTime())->setTimestamp($matchDate);

			$result = 'win';
			$scope = '';

			if (isset($match->find('.result-win')[0])) {
				$result = 'win';
				$scope = $match->find('.result-win')[0]->plaintext;
			} else if (isset($match->find('.result-loss')[0])) {
				$result = 'loss';
				$scope = $match->find('.result-loss')[0]->plaintext;
			} else if (isset($match->find('.result-draw')[0])) {
				$result = 'draw';
				$scope = $match->find('.result-draw')[0]->plaintext;
			}

			$tableData[] = [
				'date' => $matchDate->format('d/m/Y'),
				'team_1' => $match->find('.team-a a')[0]->title,
				'team_2' => $match->find('.team-b a')[0]->title,
				'result' => $result,
				'scope' => Helpers::clearString($scope),
				'competition' => Helpers::clearString($match->find('.competition a')[0]->plaintext),
			];

			$i++;

			if ($i > 5) {
				break;
			}
		}

		return $tableData;
	}
}