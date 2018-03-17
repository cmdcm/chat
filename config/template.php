<?php
return [
	// 所使用驱动
	'drive'			  => 'blade',
	// 是否开启模板编译缓存,设为false则每次都会重新编译
	'tpl_cache'   	  => true,
	// 模板起始路径
	'view_path'       => EASY_PHP_PATH . '/../views',
	'tpl_begin'   	  => '{{',
	'tpl_end'     	  => '}}',
	'tpl_raw_begin'   => '{!!',
	'tpl_raw_end'  	  => '!!}',
	'view_cache_path' => EASY_RUMTIME_PATH . '/temp', // 模板缓存目录
	// 模板文件后缀
	'view_suffix' 	  => 'php',
];