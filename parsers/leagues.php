<?php
namespace parsers;

include 'simple_html_dom.php';

use parsers\Helpers;

class Leagues
{
	public static function update()
	{
		date_default_timezone_set('Europe/Moscow');

		$leagues = \QB::table('football_bot_league')
						->get();

		$newData = [];

		foreach ($leagues as $league) {
			if ($league->parse_link) {
				$html = file_get_html(Helpers::prepareLink($league->parse_link));

				$startDate = date('Y-m-d');
				$endDate = date('Y-m-d', strtotime('+5 day'));
				$adding = false;

				foreach ($html->find('.content-column tbody .match') as $match) {
					$matchDate = Helpers::clearString($match->find('.timestamp')[1]->attr['data-value']);
					$matchDate = (new \DateTime())->setTimestamp($matchDate);

					$infoLink = $match->find('.info-button a')[0]->href;

					// Helpers::exportVar($infoLink);
					// exit();

					if (is_bool($matchDate)) {
						if ($adding == true) {
							$newData[] = [
								'team_a' => $match->find('.team-a a')[0]->title,
								'team_b' => $match->find('.team-b a')[0]->title,
								'date' => $matchDate->format('Y-m-d H:i:s'),
								'info_link' => $infoLink,
							];
						}

						continue;
					}

					if (Helpers::isDateInRange($startDate, $endDate, $matchDate->format('Y-m-d'), 'Y-m-d'))
					{
						$adding = true;

						$newData[] = [
							'team_a' => Helpers::clearString($match->find('.team-a a')[0]->title),
							'team_b' => Helpers::clearString($match->find('.team-b a')[0]->title),
							'date' => $matchDate->format('Y-m-d H:i:s'),
							'league_id' => $league->league_id,
							'info_link' => $infoLink,
						];
					}
				}
			}
		}

		if ($newData) {
			$leagues = \QB::table('football_bot_event')
							->delete();

			$leagues = \QB::table('football_bot_event')
							->insert($newData);			
		}
	}
}