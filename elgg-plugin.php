<?php

use ColdTrick\AdvancedComments\Bootstrap;

return [
	'bootstrap' => Bootstrap::class,
	'settings' => [
		'default_limit' => 25,
		'show_login_form' => 'yes',
		'allow_group_comments' => 0,
		'threaded_comments' => 0,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'comment',
			'class' => 'ThreadedComment',
			'searchable' => true,
		],
	],
	'actions' => [
		'comment/save' => [],
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
