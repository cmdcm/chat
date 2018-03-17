<?php
return [
	// 所使用驱动(path:路径解析ordinary:普通解析)
	'drive'  			 => 'path',
	// 是否多模块化
	'multi_module' 		 => false,
	// 默认模块
	'default_module'     => 'index',
	// 默认控制器
	'default_controller' => 'Index',
	// 默认执行方法
	'default_method'     => 'index',
	// 普通解析使用
	'parse' => [
		'module' 	 => 'm',
		'controller' => 'c',
		'method'     => 'a'
	 ]
];