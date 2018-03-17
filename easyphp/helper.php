<?php
/**
*  核心函数库
* ================================================
* Copy 2017
* Web: www.easyphp.com
* ================================================
* Author: Mdcm
* Date: 2017-11-14
*/
use concise\facades\Config;
use concise\View;
use concise\Container;
use concise\Session;
use concise\facades\Cookie;
use concise\Url;
if ( !function_exists('p') ) {
	/**
	 * 打印调试
	 * @param mixed $mixed
	 * @param array $methods
	 * @return void
	 */
	function p ($mixed,$methods = []) {
		if (is_bool($mixed)) {
			$mixed = $mixed ? 'true' : 'false';
		} elseif (is_null($mixed)) {
			$mixed = 'null';
		} elseif (is_object($mixed)) {
			$methods = array_merge(['toArray'],$methods);
			foreach ($methods as $method) {
				$mixed = method_exists($mixed,$method) ? call_user_func_array([$mixed,$method],[]) : $mixed;
				break;
			}
		}
   		echo "<pre style='padding:10px;border-radius:5px;background:#f5f5f5;border:1px solid #ccc;font-size:16px;'>";
   		  print_r($mixed);
   		echo "</pre>";
	}
}
if ( !function_exists('config') ) {
	
	/**
	 * 配置读取获取辅助函数
	 * @param  string $key   
	 * @param  string $value 
	 * @param  string $scope 
	 * @return bool|object   
	 */
	function config ($key = '',$value = '',$scope = 'app') {
		$key = strtolower($key);
		if (empty($key) && empty($value)) {
			return Config::ret();
		}
		if (!empty($key) && empty($value)) {
			return Config::get($key,$scope);
		}
		if (!empty($key) && !empty($value)) {
			Config::set($key,$value,$scope);
			return true;
		}
		return false;
	}
}

if ( !function_exists('view') ) {
	/**
	 * 视图辅助函数
	 * @param  string $template 
	 * @param  array $data     
	 * @return string|object
	 */
	function view ($template = '',$data = []) {
		return View::make(config()->get('drive','template'),config()->get(null,'template'),$template)->with($data);
	}
}
if (!function_exists('request')) {
	/**
	 * Request辅助函数
	 * @return object
	 */
	function request () {
		return Container::getInstance()->get('request');
	}
}
if (!function_exists('container')) {
	/**
	 * Container辅助函数
	 * @param string $name 
	 * @param array $params 
	 * @param bool $singleton
	 * @return object
	 */
	function container ($name = '',$params = [],$singleton = true) {
		return empty($name) ? Container::getInstance() : Container::getInstance()->get($name,$params,$singleton);
	}
}
if (!function_exists('app')) {
	/**
	 * App辅助函数
	 * @return object
	 */
	function app () {
		return container('app');
	}
}

if (!function_exists('asset')) {
	/**
	 * 获取资源路径
	 * @param  string $path 
	 * @return string
	 */
	function asset ($path = '') {
		$assetUrl = config('url.asset_url');
		$assetUrl = $assetUrl instanceof Closure ? $assetUrl() : $assetUrl;
		return substr($assetUrl, -1) == '/' ? $assetUrl . $path : $assetUrl . '/' . $path;
	}
}

if (!function_exists('session')) {
	/**
	 * session辅助函数
	 * @param  string $name  
	 * @param  string $value 
	 * @return mixed     
	 */
	function session ($name = '',$value = '') {
		 $session = Session::getInstance(config('session.drive'));
		 if (is_null($name)) {
		 	return $session->delete(null);
		 }
		 if (empty($name) && empty($value)) {
		 	 return $session;
		 }
		 if (!empty($name) && empty($value)) {
		 	return $session->get($name);
		 }
		 return $session->set($name,$value);
	}
}

if ( !function_exists('cookie') ) {
	
	/**
	 * Cookie辅助函数
	 * @param  string  $name   
	 * @param  string  $value  
	 * @param  integer $expire 
	 * @return mixed      
	 */
	function cookie ($name = '',$value = '',$expire = 0) {
		if (empty($name) && empty($value)) {
			return Cookie::ret();
		}
		if (!empty($name) && is_null($value)) {
			return Cookie::delete($name);
		}
		if (!empty($name) && empty($value)) {
			return Cookie::get($name);
		}
		if (!empty($name) && !empty($value)) {
			return Cookie::set($name,$value,$expire);
		}
	}
}

if (  !function_exists('url') ) {

	/**
	 * Url辅助函数
	 * @param  string $url    
	 * @param  array $params 
	 * @param  string $prefix 
	 * @return string     
	 */
	function url ($url = '',$params = [],$prefix = '') {
		return Url::get($url,$params,$prefix);
	}
}