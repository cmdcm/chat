<?php
namespace concise;

class Loader
{
	/**
	 * autoload Dir
	 * @var array
	 */
	protected static $loadDir = [];

	/**
	 * 已加载类
	 * @var array
	 */
	protected static $loadClassMap = [];

	/**
	 * 当前使用顶级命名空间
	 * @var array
	 */
	protected static $namespaces = ['app','concise'];
	/**
	 * add autoload path
	 * @param string|array $dir 
	 * @return void
	 */
	public static function addLoadPath ($dir)
	{
		 if (is_string($dir) && !empty($dir)) {
		 	 return array_push( self::$loadDir,$dir);
		 }
		 is_array($dir) && !empty($dir) ? self::$loadDir = array_merge($dir,self::$loadDir) : '';
	}
	/**
	 * 注册自动加载
	 * @param string|array $dir
	 * @return null
	 */
	public static function register ($dir = '')
	{
		self::addLoadPath($dir);
		return spl_autoload_register([__CLASS__,'autoload']);
	}
	/**
	 * 自动加载
	 * @param  string $class 
	 * @return void|boolean   
	 */
	public static function autoload ($class)
	{
		$className = str_replace("\\","/",$class);
		if (isset(self::$loadClassMap[$className])) {
			return true;			
		}
		foreach (self::$loadDir as $v) {
			$path = $v . '/' . $className . '.php';
			if (is_file($path)) {
				self::$loadClassMap[$className] = $path;
				require $path;
				return true;
			}
		}
		foreach (static::$namespaces as $v) {
			if (strpos($className,$v) !== false) {
				throw new \RuntimeException(sprintf("Class not exists:%s",$class));
			}
		}
	}
}