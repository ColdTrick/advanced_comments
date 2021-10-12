<?php

use ColdTrick\AdvancedComments\Bootstrap;
use ColdTrick\AdvancedComments\Controllers\CommentRedirector;

return [
	'plugin' => [
		'version' => '7.0.1',
	],
	'bootstrap' => Bootstrap::class,
	'settings' => [
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
	'events' => [
		'delete:after' => [
			'object' => [
				'ColdTrick\AdvancedComments\Comments::deleteChildrenComments' => [],
			],
		],
	],
	'hooks' => [
		'comments:count' => [
			'all' => [
				'ColdTrick\AdvancedComments\Comments::getCommentsCount' => [],
			],
		],
		'register' => [
			'menu:social' => [
				'Elgg\Menus\Social::registerComments' => [
					'unregister' => true,
				],
				'ColdTrick\AdvancedComments\Menus\Social::registerCommentItems' => [],
			],
		],
	],
	'routes' => [
		'view:object:comment' => [
			'path' => '/comment/view/{guid}/{container_guid?}',
			'controller' => CommentRedirector::class,
		],
	],
	'view_extensions' => [
		'elements/components/comments.css' => [
			'page/elements/comments.css' => [],
		],
		'page/components/list' => [
			'advanced_comments/before_lists' => ['priority' => 1],
		],
		'page/elements/comments' => [
			'advanced_comments/logged_out_notice' => ['priority' => 400],
		],
	],
];
