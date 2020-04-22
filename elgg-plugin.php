<?php

use ColdTrick\AdvancedComments\Bootstrap;

return [
	'bootstrap' => Bootstrap::class,
	'settings' => [
		'show_login_form' => 'yes',
		'allow_group_comments' => 0,
	],
	'hooks' => [
		'config' => [
			'comments_per_page' => [
				'ColdTrick\AdvancedComments\Comments::getCommentsPerPage' => [],
			],
		],
	],
	'view_extensions' => [
		'page/elements/comments' => [
			'advanced_comments/logged_out_notice' => ['priority' => 400],
		],
	],
];
