<?php
/**
*  配置读写
* ================================================
* Copy 2017
* Web: www.easyphp.com
* ================================================
* Author: Mdcm
* Date: 2018/2/19
*/
namespace concise;

class Config
{
	/**
	 * 存储配置
	 * @var [type]
	 */
	private $data = [];
	/**
	 * 当前使用作用域
	 * @var string
	 */
	private $scope = 'app';

	/**
	 * 初始化
	 * @param array $data 
	 * @return void 
	 */
	public function init ($data = []) {
		foreach ($data as $k => $v) {
			$this->load($v,$k);
		}
	}

	/**
	 * 加载
	 * @param  string|array $path 
	 * @return bool
	 */
	public function load ($path = [],$scope = 'app')
	{
		$this->scope = $scope ?: 'app';

		!isset($this->data[$this->scope]) ? $this->data[$this->scope] = [] : [];

		if (empty($path)) {
			return false;
		}
		if (is_string($path) && is_file($path)) {
			$this->data[$this->scope] = array_merge($this->data[$this->scope],require $path);
			return true;
		} else {
			throw new \RuntimeException(sprintf("File not exists:%s",$path));
		}
		if (is_array($path)) {
			$this->data[$this->scope] = array_merge($this->data[$this->scope],$path);
		}
		return true;
	}
	/**
	 * 设置
	 * @param string $key   
	 * @param string $value 
	 * @param string $scope 
	 * @return object
	 */
	public function set ($key,$value = '',$scope = 'app')
	{
		$this->scope = $scope ?: 'app';
		if (strpos($key, '.')) {
			$keys = explode('.', $key);
			isset($this->data[$this->scope][$keys[0]]) ? $this->data[$this->scope][$keys[0]][$keys[1]] = $value : '';
		} else {
			isset($this->data[$this->scope][$key]) ? $this->data[$this->scope][$key] = $value : '';
		}
		return $this;
	}
	
	/**
	 * 获取
	 * @param  string $key   
	 * @param  string $scope 
	 * @return string       
	 */
	public function get ($key = '',$scope = 'app') {
		$this->scope = $scope ?: 'app';
		if (empty($key)) {
			return $this->data[$this->scope];
		}
		if (strpos($key, '.')) {
			$keys = explode('.', $key);
			return isset($this->data[$this->scope][$keys[0]]) ? $this->data[$this->scope][$keys[0]][$keys[1]] : '';
		} else {
			return isset($this->data[$this->scope][$key]) ? $this->data[$this->scope][$key] : '';
		}
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