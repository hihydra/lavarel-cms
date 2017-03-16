<?php
return [
	// 自定义登录字段
	'username' 	=> 'username',
	// 重置用户密码
	'reset' 	=> '123456',
	// 分页
	'list' => [
		'start'=> 0,
		'length' => 10,
	],
	/**
	 * 全局状态
	 * active 	正常
	 * ban 		禁用
	 * audit 	待审核
	 * trash	回收站
	 * destory 	彻底删除
	 */
	'status' => [
		'active' => 1,
		'ban' => 2,
		'audit' => 3,
		'trash' => 99,
		'destory' => -1
	],
	/**
	 *栏目类型
	 *'cover'    封面页
	 *'column'   栏目页
	*/
	'categoryType'  => [
		'cover'   => 1,
		'column'  => 2

	],
	'permission' => [
		// 控制是否显示查看按钮
		'show' => false,
	],
	'role' => [
		// 控制是否显示查看按钮
		'show' => true,
	],
	'user' => [
		// 控制是否显示查看按钮
		'show' => true,
	],
	// 缓存
	'cache' => [
		'menuList' => 'menuList',
		'categoryList' => 'categoryList'
	]
];