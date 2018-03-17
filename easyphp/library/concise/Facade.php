<?php
/**
* Facades  
* ================================================
* Copy 2017
* Web: www.easyphp.com
* ================================================
* Author: Mdcm
* Date: 2017-11-14
*/
namespace concise;

abstract class Facade
{
	private static $classMap = [];

	/**
	 * Get Facdes Instance
	 * @return object
	 */
	public static function getInstance ()
	{
		$facadeName = static::getFacadeAccessor();
		if (is_object($facadeName)) {
			return $facadeName;
		}
		if (isset(self::$classMap[$facadeName]) && is_object(self::$classMap[$facadeName])) {
			return self::$classMap[$facadeName];
		}
		$instance = Ioc::getInstance($facadeName);
		self::$classMap[$facadeName] = $instance;
		return $instance;
	}	
	/**
	 * Get FacadeAccessor Name 
	 * @return string     
	 */
	public static function getFacadeAccessor ()
	{
		throw new \RuntimeException('Facade does not implement getFacadeAccessor method.');
	}

	public static function __callStatic ($method,$params)
	{
		$instance = static::getInstance();
		if (method_exists($instance, $method)) {
			return call_user_func_array([$instance,$method],$params);
		} 
		throw new \BadMethodCallException('method not exists:' . __CLASS__ . '->' . $method);
	}
}