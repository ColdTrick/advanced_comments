<?php

use ColdTrick\AdvancedComments\Bootstrap;
use ColdTrick\AdvancedComments\Controllers\CommentRedirector;

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
	'events' => [
		'delete:after' => [
			'object' => [
				'ColdTrick\AdvancedComments\Comments::deleteChildrenComments' => [],
			],
		],
	],
	'hooks' => [
		'config' => [
			'comments_per_page' => [
				'ColdTrick\AdvancedComments\Comments::getCommentsPerPage' => [],
			],
		],
		'register' => [
			'menu:social' => [
				'_elgg_comments_social_menu_setup' => [
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
		'page/elements/comments' => [
			'advanced_comments/logged_out_notice' => ['priority' => 400],
		],
	],
];
