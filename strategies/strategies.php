<?php

$strategies = [
	[
		'message' => ['/start'], 
		'memory-conditions' => [
			'$memory->theme_id == 0',
			'$memory->mode_id == 0',
			'$memory->word_id == 0',
		],
		'handler' => [
			'controller' => 'Main',
			'action' => 'menu', 
		],
	],
	[
		'message' => ['/theme'], 
		'memory-conditions' => [],
		'handler' => [
			'controller' => 'Main',
			'action' => 'theme', 
		],
	],
	[
		'message' => ['/mode'], 
		'memory-conditions' => [],
		'handler' => [
			'controller' => 'Main',
			'action' => 'mode', 
		],
	],
	[
		'message' => ['/next'], 
		'memory-conditions' => [],
		'handler' => [
			'controller' => 'Learn',
			'action' => 'next', 
		],
	],
	[
		'message' => ['/translate'], 
		'memory-conditions' => [],
		'handler' => [
			'controller' => 'Learn',
			'action' => 'translate', 
		],
	],
	[
		'message' => ['/reverse'], 
		'memory-conditions' => [],
		'handler' => [
			'controller' => 'Learn',
			'action' => 'translate', 
		],
	],
];
