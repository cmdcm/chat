<?php
/**
*  服务容器
* ================================================
* Copy 2017
* Web: www.easyphp.com
* ================================================
* Author: Mdcm
* Date: 2018/2/19
*/
namespace concise;

class Container
{
	/**
	 * 绑定列表
	 * @var array
	 */
	protected $bindings = [];

	/**
	 * 存储对象列表
	 * @var array
	 */
	private $classMap = [];

	/**
	 * 单例对象
	 * @var null
	 */
	private static $instance = null;

	// 禁止构造
	private function __construct () {}

	// 禁止克隆
	private function __clone () {}

	/**
	 * 获取单例对象
	 * @return object
	 */
	public static function getInstance ()
	{
		if (is_null(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}
	/**
	 * 绑定
	 * @param  string $abstract  
	 * @param  object $concreate 
	 * @return object
	 */
	public function bind ($abstract,$concreate = null)
	{
		if (is_array($abstract)) {
			foreach ($abstract as $key => $value) {
				!isset($this->bindings[$key]) ? $this->bindings[$key] = $value : '';
			}
			return $this;
		}
		if (isset($this->bindings[$abstract])) {
			return $this;
		}
		$this->bindings[$abstract] = $concreate;
		return $this;
	}
	/**
	 * 使用
	 * @param  string $abstract 
	 * @param  array $params   
	 * @return string|array|object         
	 */
	public function make ($abstract,$params = [])
	{
		return isset($this->bindings[$abstract]) ? call_user_func_array($this->bindings[$abstract], $params): '';
	}

	/**
	 * 设置
	 * @param string|array $name
	 * @param string|object $value
	 * @return object
	 */
	public function set ($name,$value = '') {
		if (is_array($name)) {
			foreach ($name as $k => $v) {
				!isset($this->classMap[$k]) ? $this->classMap[$k] = $v : '';
			}
		} else {
			!isset($this->classMap[$name]) ? $this->classMap[$name] = $value : '';
		}
		return $this;
	}
	/**
	 * 获取
	 * @param  string $abstract 
	 * @param  array $params 
	 * @param  bool $singleton 
	 * @return object         
	 */
	public function get ($abstract,$params = [],$singleton = true)
	{
		if (!isset($this->classMap[$abstract])) {
			throw new \RuntimeException(sprintf("container object not exists:%s",$abstract));
		}
		if (is_object($this->classMap[$abstract])) {
			return $this->classMap[$abstract];
		}
		$object = Ioc::getInstance($this->classMap[$abstract],$params);
		if ($singleton) {
			$this->classMap[$abstract] = $object;
		}
		return $object;
	}
}