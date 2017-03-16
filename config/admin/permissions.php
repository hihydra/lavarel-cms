
<?php
return [
	'permission' => [
		'list' 		=> 'permission.list',
		'create' 	=> 'permission.create',
		'edit' 		=> 'permission.edit',
		'destroy' 	=> 'permission.destroy',
	],
	'role' => [
		'list' 		=> 'role.list',
		'create' 	=> 'role.create',
		'edit' 		=> 'role.edit',
		'destroy' 	=> 'role.destroy',
		'show' 		=> 'role.show',
	],
	'user' => [
		'list' 		=> 'user.list',
		'create' 	=> 'user.create',
		'edit' 		=> 'user.edit',
		'destroy' 	=> 'user.destroy',
		'show' 		=> 'user.show',
		'reset' 	=> 'user.reset',
	],
	'menu' => [
		'list' 		=> 'menu.list',
		'create' 	=> 'menu.create',
		'edit' 		=> 'menu.edit',
		'orderable' => 'menu.edit',
		'destroy' 	=> 'menu.destroy',
		'show' 		=> 'menu.show',
	],
	'log' => [
		'list' 		=> 'log.list',
		'destroy' 	=> 'log.destroy',
		'show' 		=> 'log.show',
		'download' 	=> 'log.download',
		'filter' 	=> 'log.filter',
	],
	'category' => [
		'list' 		=> 'category.list',
		'create' 	=> 'category.create',
		'edit' 		=> 'category.edit',
		'orderable' => 'category.edit',
		'destroy' 	=> 'category.destroy',
		'show' 		=> 'category.show',
	],
	'article' => [
		'list' 		=> 'article.list',
		'create' 	=> 'article.create',
		'edit' 		=> 'article.edit',
		'audit'     => 'article.audit',
		'trash'     => 'article.trash',
		'undo'      => 'article.undo',
		'destroy' 	=> 'article.destroy',
		'show' 		=> 'article.show',
	],
	'tag' => [
		'list' 		=> 'tag.list',
		'create' 	=> 'tag.create',
		'edit' 		=> 'tag.edit',
		'destroy' 	=> 'tag.destroy',
		'show' 		=> 'tag.show',
	],
	'system' => [
		'list' => 'system.index',
		'blog' => 'system.blog',
	]
];