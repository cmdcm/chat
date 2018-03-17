<?php
namespace concise;

class Cookie 
{
	/**
	 * cookie配置
	 * @var array
	 */
	protected $config;

	// 初始化
	public function __construct ()
	{
		$this->config = config('cookie');
	}
	/**
	 * 获取cookie数据
	 * @param  string $name    
	 * @param  string $default 
	 * @return string      
	 */
	public function get ($name,$default = '')
	{
		return Input::get($_COOKIE,$name,$default);
	}
	/**
	 * 设置cookie
	 * @param string  $name     
	 * @param string  $value    
	 * @param integer $expire   
	 * @param string  $path     
	 * @param string  $domain   
	 * @param boolean $secure   
	 * @param boolean $httpOnly 
	 * @return object
	 */
	public function set ($name , $value , $expire = 0 , $path = '' , $domain = '' , $secure = false , $httpOnly = false)
	{
		$expire = empty($expire) ? $this->config['expire'] + time() : $expire + time();
		$path   = empty($path)   ? $this->config['path'] : $path;
		$domain = empty($domain) ? $this->config['domain'] : $domain;
		$secure = isset($secure) ? $secure : $this->config['secure'];

		setcookie($name , $value , $expire , $path , $domain , $secure, $httpOnly);
		$_COOKIE[$name] = $value;
		return $this;
	}
	/**
	 * 删除cookie
	 * @param  string $name 
	 * @return mixed       
	 */
	public function delete ($name)
	{
		 unset($_COOKIE[$name]);
		 return setcookie($name) || setcookie($name , '' , now()-1);
	}
	/**
	 * 返回当前对象
	 * @return object
	 */
	public function ret ()
	{
		return $this;
	}
}