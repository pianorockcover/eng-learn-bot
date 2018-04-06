<?php

$strategies = [
	[
		'message' => ['📍 Дернуть за рычаг!'], 
		'memory-conditions' => [],
		'handler' => [
			'controller' => 'Main',
			'action' => 'result', 
		],
	],
	[
		'message' => '', 
		'memory-conditions' => [
			'$memory->sayed_hello == 0',
		],
		'handler' => [
			'controller' => 'Main',
			'action' => 'sayHello', 
		],
	],

	[
		'message' => '', 
		'memory-conditions' => [
			'$memory->sayed_hello == 1',
		],
		'handler' => [
			'controller' => 'Main',
			'action' => 'result', 
		],
	],

];
