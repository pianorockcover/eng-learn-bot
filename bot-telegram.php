<?php
	use app\Bot;
	use Telegram\Bot\Api; 

	include 'app/autoload.php';
	include 'vendor/autoload.php';
	include 'strategies/strategies.php';
	include 'config.php';

	try {
	    $telegram = new Api($config['telegram-config']['token']); 
	    
		if (isset($serviceMess) && isset($serviceUserID)) {
			$params = [
		    	'userName' => '',
		    	'userID' => $serviceUserID,
		    	'messenger_id' => 1,
		    ];		

		    $message = $serviceMess;
		} else {
		    $result = $telegram->getWebhookUpdates();
			$message = $result["message"]["text"];
			// $message = "test";
			// file_put_contents("telegram_log", json_encode($result));
		    
		    $params = [
		    	'name' => $result["message"]["from"]['first_name'],
		    	'userID' => $result["message"]["chat"]["id"],
		    	'messenger_id' => 1,
		    ];

		    if (isset($result['callback_query'])) {
		    	$message = $result["callback_query"]["data"];

		    	$params = [
			    	'name' => $result["callback_query"]["from"]['first_name'],
			    	'userID' => $result["callback_query"]["from"]["id"],
			    	'messanger_id' => 1,
			    ];
		    }
		}

		$bot = new Bot($strategies, $config, $params);
		$replies = $bot->reply($message);

		foreach ($replies as $reply) {
			if (count($reply->keyboard['inline_keyboard'])) {
				$reply_markup = json_encode([
					'inline_keyboard' => $reply->keyboard['inline_keyboard'], 
				]);
			} else {
				if (count($reply->keyboard['keyboard'])) {
					$reply_markup = json_encode([
						'keyboard' => $reply->keyboard['keyboard'], 
						'resize_keyboard' => true, 
						'one_time_keyboard' => true,
					]);
				}
			}

			if (isset($reply->image)) {
				$telegram->sendPhoto([
					'chat_id' => $params['userID'], 
					'photo' => $reply->image, 
					'caption' => $reply->textTelegram,
					'reply_markup' => $reply_markup,
				]);	

				continue;
			}

			if (isset($reply->audio)) {
				$telegram->sendAudio([
				  'chat_id' => $params['userID'], 
				  'audio' => $reply->audio,
				  'reply_markup' => $reply_markup,
				]);

				continue;
			}

			if (isset($reply->video)) {
				$telegram->sendVideo([
				  'chat_id' => $params['userID'], 
				  'video' => $reply->video,
				  'reply_markup' => $reply_markup,
				]);

				continue;
			}

			if (isset($reply->location)) {
				$telegram->sendLocation([
				  'chat_id' => $params['userID'], 
				  'latitude' => $reply->location['latitude'],
				  'longitude' => $reply->location['longitude'],
				  'reply_markup' => $reply_markup,
				]);
			}

			if (isset($reply->document)) {
				$telegram->sendDocument([
				  'chat_id' => $params['userID'], 
				  'document' => $reply->document,
				  'caption' => $reply->textTelegram,
				  'reply_markup' => $reply_markup,
				]);
			}

			if (isset($reply->textTelegram)) {
				$telegram->sendMessage([
				  'chat_id' => $params['userID'], 
				  'text' => $reply->textTelegram,
				  'reply_markup' => $reply_markup,
				  'parse_mode' => 'MARKDOWN',
				]);
			}	
		}
	} catch (\Exception $e) {
		$logDir = 'logs';
		if (!file_exists('logs')) {
			$logDir = 'logs';
		}

		file_put_contents('telegram_log.txt', date('d.m.Y H:i')."\t".$e->getMessage()."\t".$e->getFile()."\t".$e->getLine()."\n", FILE_APPEND | LOCK_EX);
	}

