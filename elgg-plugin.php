<?php

use ColdTrick\AdvancedComments\Bootstrap;

require_once(dirname(__FILE__) . '/lib/functions.php');

return [
	'bootstrap' => Bootstrap::class,
	'settings' => [
		'default_order' => 'desc',
		'default_auto_load' => 'no',
		'user_preference' => 'yes',
		'show_login_form' => 'yes',
		'allow_group_comments' => 0,
	],
];
