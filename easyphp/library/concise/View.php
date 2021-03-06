<?php
namespace concise;

class View
{
	/**
	 * 单例对象
	 * @var object
	 */
	protected static $instance;
	/**
	 * 模板引擎配置
	 * @var array
	 */
	protected $config = [
        // 是否开启模板编译缓存,设为false则每次都会重新编译
        'tpl_cache'   	  => true,
        // 模板起始路径
        'view_path'       => '',
        'tpl_begin'   	  => '{{',
        'tpl_end'     	  => '}}',
        'tpl_raw_begin'   => '{!!',
        'tpl_raw_end'  	  => '!!}',
        'view_cache_path' => EASY_RUMTIME_PATH . '/temp', // 模板缓存目录
        // 模板文件后缀
        'view_suffix' 	  => 'php',
	];
	/**
	 * 存储视图变量
	 * @var array
	 */
	protected $data = [];
	/**
	 * 模板引擎实例
	 * @var null
	 */
	protected $template = null;

	/**
	 * 模板路径
	 * @var string
	 */
	protected $templatePath;
	// 初始化
	protected function __construct ($config = []) {
		$this->config($config);
		$this->boot();
	}
	private function __clone () {}


	protected function boot () {}
	/**
	 * 获取单例对象
	 * @param  string $engine
	 * @param  array $config
	 * @return object
	 */
	public static function make ($engine = '',$config = [],$templatePath = '')
	{
		$class = $engine == '' ? '\\concise\\view\\Native' : '\\concise\\view\\' . ucfirst($engine);

		if (is_null(self::$instance)) {
			if (class_exists($class)) {
				self::$instance = new $class($config,$templatePath);
			} else {
				throw new \concise\exception\ClassNotException('class is not exists:' . $class);
			}
		}
		self::$instance->templatePath = $templatePath;
		return self::$instance;
	}
	/**
	 * 配置处理
	 * @param  string $name  
	 * @param  string $value 
	 * @return mixed
	 */
	public function config ($name = '',$value = '')
	{
		if (is_array($name)) {
			$this->config = array_merge($this->config,$name);
		} elseif (empty($value)) {
			return $this->config[$name];
		} elseif (empty($name) && empty($value)) {
			return $this->config;
		} else {
			!empty($name) ? $this->config[$name] = $value : '';
		}
		return $this;
	}
	/**
	 * 解析模板
	 * @param  string $template 
	 * @return string
	 */
	protected function parseTemplate ($template = '')
	{
		$request = Container::getInstance()->get('request');
		if (empty($template)) {
			$file = sprintf("%s%s",
				implode('/',[ $this->config['view_path'], $request->module(),$request->controller(),$request->action()]),'.' . $this->config['view_suffix']
			);
		} else {
			$paths = explode('.',$template);
			switch (true) {
		 		case count($paths) == 1:
		 			$module 	= $request->module();
		 			$controller = $request->controller();
		 			$method		= $paths[0]; 
		 			break;
		 		case count($paths) == 2:
		 			$module 	= $request->module();
		 			$controller = $paths[0];
		 			$method		= $paths[1]; 
		 			break;
		 		default:
		 			$module 	= $paths[0];
		 			$controller = $paths[1];
		 			$method		= $paths[2]; 
		 			break;
			}
			$file = sprintf("%s%s",
				implode('/',[ $this->config['view_path'],$module,lcfirst($controller),$method]),'.' . $this->config['view_suffix']
			);
		}
		if (!is_file($file)) {
			throw new \concise\exception\TemplateNotFoundException("Template not exists:" . $file,$file);
		}
		return $file;
	}
	/**
	 * 分配视图变量
	 * @param  string $name  
	 * @param  string $value 
	 * @return object    
	 */
	public function with ($name,$value = '')
	{
		if (is_array($name)) {
			foreach ($name as $k => $v) {
				$this->data[$k] = $v;
			}
		} else {
			$this->data[$name] = $value;
		}
		return $this;
	}
	/**
     * 无方法执行
     * @param  string $method 方法名称 
     * @param  array $args   参数列表
     * @return void
     */
	public function __call ($method,$args)
	{
        if (strpos($method,'with') !== false) {
            return $this->with(lcfirst(substr($method,-(strlen($method) - 4))),$args[0]);
        }
        if (!is_null($this->template)) {
        	if (method_exists($this->template, $method)) {
        		return call_user_func_array([$this->template,$method],$args);
        	}
        }
        throw new \RuntimeException(__CLASS__ . "->" . $method . ' is not exists!');
	}
}