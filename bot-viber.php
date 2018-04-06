<?php
	use app\Bot;

	include 'app/autoload.php';
	include 'vendor/autoload.php';
	include 'strategies/strategies.php';
	include 'config.php';


	$apiKey = $config['viber-config']['api-key'];

	if (isset($serviceMess) && isset($serviceUserID)) {
		$params = [
	    	'userName' => '',
	    	'userID' => $serviceUserID,
	    	'messenger_id' => 4,
	    ];	

		$bot = new Bot($strategies, $config, $params);
		$replies = $bot->reply($serviceMess);

		$data = [];
		$data['auth_token'] = $apiKey;
		$data['receiver'] = $serviceUserID;
		$data['min_api_version'] = 2;
		$data['type'] = 'rich_media';
		$data['rich_media'] = ["Buttons"];

		foreach ($replies as $reply) {

			if (isset($reply->image)) {
				$data['rich_media']["Buttons"][] = [
			      "Columns" => 6,
			      "Rows" => 3,
			      // "Text" => "{$_POST['text']}",
			      "ActionBody" => "",
			      "TextSize" => "medium",
			      "TextVAlign" => "middle",
			      "TextHAlign" => "left",
			      "Image" => "{$reply->image}"
			    ];

			    continue;
			}

			$data['rich_media']["Buttons"][] = [
				"Columns" => 6,
				"Rows" => 3,
				"Text" => "{$reply->textViber}",
				"ActionBody" => "",
				"TextSize" => "medium",
				"TextVAlign" => "middle",
				"TextHAlign" => "left"
			];

		}
		
		$ch = curl_init("https://chatapi.viber.com/pa/send_message");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($ch);

	} else {

		$viberBotSender = new Viber\Api\Sender([
		    'name' => $config['viber-config']['name'],
		    'avatar' => $config['viber-config']['avatar'],
		]);


		try {
		    $viberBot = new Viber\Bot(['token' => $apiKey]);
		    $viberBot->onText('(.*)', function ($event) use ($viberBot, $viberBotSender, $config, $strategies) {
				$message = $event->getMessage()->getText();
				    
			    $params = [
			   		'userID' => $event->getSender()->getId(),
				    // 'userID' => '666',
			   		'userName' => '',
			   		'messenger_id' => 4,
			    ];

				$bot = new Bot($strategies, $config, $params);
				$replies = $bot->reply($message);

				foreach ($replies as $reply) {
					if (isset($reply->image) && isset($reply->fbKeyboard)) {
						// $content = (new \Viber\Api\Message\Picture())
			   //              ->setSender($viberBotSender)
			   //              ->setReceiver($event->getSender()->getId())
			   //              ->setText($reply->textViber)
			   //              ->setMedia($reply->image);
					  $data = [];
					  $data['auth_token'] = $config['viber-config']['api-key'];
					  $data['receiver'] = $event->getSender()->getId();
					  $data['min_api_version'] = 2;
					  // $data['text'] = "The message to send to user";
					  $data['type'] = 'rich_media';
					  $data['rich_media'] = [
						    "Buttons" => [
						    	[
							      "Columns" => 6,
							      "Rows" => 3,
							      // "Text" => "{$_POST['text']}",
							      "ActionBody" => "",
							      "TextSize" => "medium",
							      "TextVAlign" => "middle",
							      "TextHAlign" => "left",
							      "Image" => "{$reply->image}"
							    ],
								[
							      "Columns" => 6,
							      "Rows" => 3,
							      "Text" => "{$reply->textViber}",
							      "ActionBody" => "",
							      "TextSize" => "medium",
							      "TextVAlign" => "middle",
							      "TextHAlign" => "left"
							    ],
							    [
							      "Columns" => 6,
							      "Rows" => 1,
							      "ActionType" => "reply",
							      "ActionBody" => "{$reply->fbKeyboard[0]['value']}",
							      "Text" => "<font color=#ffffff>{$reply->fbKeyboard[0]['title']}</font>",
							      "TextSize" => "large",
							      "TextVAlign" => "middle",
							      "TextHAlign" => "middle",
							      "Image" => "https://test.njdstudio.com/vb-btn.jpg"
							    ],
							    
						    ],
					  ];

					  $ch = curl_init("https://chatapi.viber.com/pa/send_message");
					  curl_setopt($ch, CURLOPT_POST, 1);
					  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					  $result = curl_exec($ch);

					  continue;

					} else if (isset($reply->textViber)) {
				        $content = (new Viber\Api\Message\Text())
				            ->setSender($viberBotSender)
				            ->setReceiver($event->getSender()->getId())
				            ->setText($reply->textViber);
					}

					if (isset($content)) {
						if (count($reply->keyboard['keyboard'])) {
							$buttons = toUsualArray($reply->keyboard['keyboard']);
							$buttonsFormatted = [];

							foreach ($buttons as $button) {
								$buttonsFormatted[] = (new \Viber\Api\Keyboard\Button())
			                        ->setActionType('reply')
			                        ->setBgColor('#b5afe2')
			                        ->setTextSize('small')
			                        ->setActionBody($button)
			                        ->setText($button);
							}

							$content->setKeyboard(
			                    (new \Viber\Api\Keyboard())
			                    ->setButtons($buttonsFormatted)
			                );
						}

				        $answer = $viberBot->getClient()->sendMessage($content);
				        // file_put_contents('logs/viber.txt', json_encode($answer));
					}
				}
		    })
		    ->run();
		} catch (Exception $e) {
		    file_put_contents('logs/viber.txt', date('d.m.Y H:i')."\t".$e->getMessage()."\t".$e->getFile()."\t".$e->getLine()."\n", FILE_APPEND | LOCK_EX);
		}

	}
	function toUsualArray($multiarray)
	{
		$iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($multiarray));

		$result = array();
		foreach($iterator as $value) {
			$result[] = $value; 
		}

		return $result;
	}