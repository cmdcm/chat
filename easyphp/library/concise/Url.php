<?php
namespace concise;

class Url
{
	/**
	 * base url
	 * @var string
	 */
	private static $url;

	/**
	 * 配置
	 * @var array
	 */
	private static $config = [];

	/**
	 * 所选驱动
	 * @var string
	 */
	private static $drive;

	/**
	 * url绑定
	 * @param  mixed $baseUrl 
	 * @return void        
	 */
	public static function build ($baseUrl = '')
	{
		$url = empty($baseUrl) ? '' : $baseUrl instanceof \Closure ? $baseUrl() : $baseUrl;

		if ($url == '') {
			$url = self::getDefaultUrl();
		}
		self::$url 	  = $url;
		self::$config = config('url');
		self::$drive  = config()->get('drive','router');
	}
	/**
	 * 获取url
	 * @param  string $url    
	 * @param  array $params 
	 * @param  string $prefix 
	 * @return string        
	 */
	public static function get ($url,$params = [],$prefix = '')
	{
		if (is_string($url) && strpos($url,'.') !== false) {
			$url = str_replace(".","/",$url);
		}
		if (is_array($url)) {
			$url = implode("/",$url);
		}
		$requestUrl = ltrim(self::$url,'/') . '/' . ltrim($url,'/');
		return $requestUrl . self::parseParams($params,empty($prefix) ? self::$config['prefix'] : $prefix);
	}
	/**
	 * 获取默认url
	 * @return string
	 */
	public static function getDefaultUrl ()
	{
		return "http://" . $_SERVER['HTTP_HOST'];
	}
	/**
	 * 解析参数
	 * @param  array $params 
	 * @return string     
	 */
	public static function parseParams ($params = [],$prefix = '')
	{
		$urlParam = '';
		if (empty($params)) {
			return $urlParam;
		}
		$prefix = empty($prefix) ? $prefix : '.' . $prefix;
		if (is_string($params)) {
			return $params . (self::$drive == 'path' ? $prefix : '');
		}
		if (self::$drive == 'ordinary') {
			return '?' . http_build_query($params);
		}
		foreach ($params as $k => $v) {
			$urlParam .= '/' . $k . '/' . $v;
		}
		return $urlParam . $prefix;
	}
}