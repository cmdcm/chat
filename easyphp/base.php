<?php
// 运行开始
defined('EASY_PHP_START') 	 || define('EASY_PHP_START',microtime(true));
// 框架基础目录
defined('EASY_PHP_PATH')	 || define('EASY_PHP_PATH',str_replace("\\","/",__DIR__));
// 框架类库目录
defined('EASY_PHP_LIBRARY')  || define('EASY_PHP_LIBRARY',EASY_PHP_PATH . '/library');
// 框架核心类库
defined('EASY_PHP_CORE') 	 || define('EASY_PHP_CORE',EASY_PHP_LIBRARY . '/concise');
// 配置路径
defined('EASY_CONFIG_PATH')  || define("EASY_CONFIG_PATH",realpath(EASY_PHP_PATH . '/../config'));
// vendor 
defined('EASY_VENDOR_PATH')  || define('EASY_VENDOR_PATH',realpath(EASY_PHP_PATH . '/../vendor'));
// Runtime
defined('EASY_RUMTIME_PATH') || define('EASY_RUMTIME_PATH',realpath(EASY_PHP_PATH . '/../runtime'));
// 引入
$loads = require EASY_CONFIG_PATH . '/autoload.php';
array_walk($loads,function ($file) {
	if(is_file($file)) {
		return require $file;
	}
	throw new \RuntimeException(sprintf("File not exists:%s",$file));
});
\concise\Loader::register([
	EASY_PHP_LIBRARY
]);