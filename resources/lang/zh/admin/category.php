<?php
return [
	'title' 	=> '分类管理',
	'desc' 		=> '分类列表',
	'create' 	=> '添加分类',
	'edit' 		=> '修改分类',
	'info' 		=> '分类信息',
	'model' 	=> [
		'id' 			=> 'ID',
		'name' 			=> '分类名称',
		'language' 		=> '语言',
        'icon' 			=> '图标',
        'pid' 			=> '分类关系',
        'slug' 			=> '分类权限',
        'url' 			=> '分类链接',
        'description' 	=> '描述',
        'sort' 			=> '排序',
        'status'        => '是否启用',
        'type'          => '栏目类型',
        'created_at' 	=> '创建时间',
        'updated_at' 	=> '修改时间',
	],
	'type' => [
		'cover'   => '封面页',
		'column'  => '栏目页',
	],
	'action' => [
		'create' => '<i class="fa fa-plus"></i> 添加分类',
	],
	'welcome' => 'Welcome ！',
	'sorry' => 'Sorry ！',
	'description' => '你可以操作左侧分类列表，或者点击下面添加按钮新增分类！',
	'description_sorry' => '暂无权限添加分类',
	'topCategory' => '顶级分类',
	'moreIcon' => '更多图标请查看 <a href="http://fontawesome.io/icons/" target="_black">Font Awesome</a>',

];