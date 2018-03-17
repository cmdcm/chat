<?php
namespace concise;
class Session
{
	/**
	 * session配置
	 * @var array
	 */
	protected $config;

	/**
	 * 单例对象
	 * @var object
	 */
	private static $instance = null;

	// 初始化
	protected function __construct ()
	{
		$this->config = config('session');
		ini_set('session.gc_maxlifetime',$this->config['life_time']);
	}

	private function __clone () {}
	/**
	 * 获取单例对象
	 * @param  string $drive 
	 * @return object   
	 */
	public static function getInstance ($drive = '')
	{
		if (is_null(self::$instance) || !self::$instance instanceof self) {
			$class = $drive == '' ? '' : "\\concise\\session\\" . $drive . "\\Repository";
			if (class_exists($class)) {
				self::$instance = new $class();
			} else {
				self::$instance = new self();
			}
			!isset($_SESSION) && session_start();
		}
		return self::$instance;
	}
	/**
	 * 获取
	 * @param  string $name    
	 * @param  string $default 
	 * @return string         
	 */
	public function get ($name = '',$default = '')
	{
		return Input::get($_SESSION,$name,$default);
	}
	/**
	 * 设置
	 * @param string $name  
	 * @param mixed $value
	 * @return object 
	 */
	public function set ($name,$value = '')
	{
		Input::set($_SESSION,$name,$value);
		return $this;
	}
	/**
	 * 删除
	 * @param  string $name 
	 * @return object       
	 */
	public function delete ($name)
	{
		is_null($name) ? session_destroy() && $_SESSION = [] : Input::delete($_SESSION,$name);
		return $this;
	}
}