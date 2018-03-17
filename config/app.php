<?php
return [
	 // 是否为调试模式
	'debug' 			=> false,
	// 应用顶级命名空间
	'namespace'			=> 'app',
	// 时区
	'date_time_zone'	=> 'PRC',
	// 默认返回类型
	'return_type'		=> 'json',
	// 错误处理
	'error_handle'		=> [
		 // 驱动
		 'drive'   		=> 'whoops',
		 // 错误标题
		 'title'   		=> '哎呀~页面出错啦~',
		 // 错误提示消息(debug为false显示)
		 'message'		=> 'Whoops! There was an error.',
		 // 错误处理函数(debug为false触发)
		 'termination'  => function ($error) {
		 	include  EASY_PHP_PATH . '/tpl/termination.php';
		 	exit;
		 }
	 ],
	 // 日志记录
	 'log' => [
	 	 // 是否开启日志记录
	 	 'is_record'  => true, 
	 	 // 文件名格式
	 	 'format' 	  => function () {
	 	 	 return date('Y_m_d');
	 	 },
	 	 'ext' 		  => 'log'
	 ],
	 // url
	 'url' => [
	 	 // 资源目录url
	 	 'asset_url' => function () {
	 	 	return '/assets';
	 	 },
	 	 // 根目录url
	 	 'base_url'	 => function () {
	 	 	return '/';
	 	 },
	 	 // url后缀
	 	 'prefix'	 => 'html'
	 ],
	 // cookie配置
	 'cookie' => [
	 	// 存储时间
	 	'expire' => 3600,
	 	// 存储路径
	 	'path'	 => '/',
	 	// 有效域名
	 	'domain' => '',
	 	// 是否为https传输
	 	'secure' => false
	 ],
	 // session配置
	 'session' => [
	 	// 驱动
	 	'drive' 	=> '',
	 	// 过期时间
	 	'life_time' => 1440,
	 	// 存储数据库表名
	 	'table'     => 'session_data'
	 ]
];