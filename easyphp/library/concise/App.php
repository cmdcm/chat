<?php
namespace concise;
use concise\app\Application as BaseApplication;
use concise\facades\Config;
use concise\ioc\ServiceContainer;
use concise\facades\Log;
class App extends BaseApplication
{
	/**
	 * 版本
	 * @var string
	 */
	const VERSION = '1.0';
	/**
	 * 服务容器
	 * @var object
	 */
	private $container;

	/**
	 * 是否为调试模式
	 * @var bool
	 */
	public static $debug;

	/**
	 * 服务提供容器
	 * @var object
	 */
	public $serviceContainer;
	
	/**
	 * 运行开始
	 * @param  string $appPath 
	 * @param array $params 
	 * @return mixed
	 */
	public function run ($appPath,$params = [])
	{
		// 添加自动加载路径
		Loader::addLoadPath(dirname($appPath));
		// 配置初始化
		Config::init([
			'app' 	   => EASY_CONFIG_PATH . '/app.php',
			'router'   => EASY_CONFIG_PATH . '/router.php',
			'template' => EASY_CONFIG_PATH . '/template.php',
			'database' => EASY_CONFIG_PATH . '/database.php',
			'provider' => EASY_CONFIG_PATH . '/provider.php'
		]);
		
		self::$debug = config('debug');

		// 注册错误处理
		Error::register();
		
		// URL绑定
		Url::build(config('url.base_url'));		
		
		// 服务提供
		$this->serviceContainer = ServiceContainer::getInstance(config()->get(null,'provider'));
		
		$this->container = Container::getInstance()->set([
			'router'   => 'concise\Router',
			'request'  => 'concise\Request',
			'date'	   => 'concise\Date'
		])->bind([
			'response' => function ($data,$type,$code = 200,$header = []) {
				return \concise\Response::create($data,$type,$code,$header);
			}
		]);
		$this->container->get('request')->set(['appPath' => $appPath]);

		$this->container->get('date',['dateTimeZone' => config('date_time_zone')]);

		return $this->handleRequest($params);
	}
	/**
	 * 处理请求
	 * @param array $params 
	 * @return object
	 */
	public function handleRequest ($params)
	{
		$result = $this->container->get('router')->dispatcher($params);
		$methods = ['fetch'];
		if (is_object($result)) {
			foreach ($methods as $method) {
				$result = method_exists($result, $method) ? call_user_func_array([$result,$method],[]) : $result;
			}
		}
		$type = is_array($result) || is_object($result) ? config('return_type') : '';
		return $this->container->make('response',[$result,$type,200,[]]);
	}
	/**
	 * 获取服务容器已经绑定的对象
	 * @param  string  $name      
	 * @param  array  $params    
	 * @param  boolean $singleton 
	 * @return object             
	 */
	public function getContainer ($name,$params = [],$singleton = true)
	{
		return $this->container->get($name,$params,$singleton);
	}
	/**
	 * 获取服务提供者提供的对象
	 * @param  string $service 
	 * @return object          
	 */
	public function getServiceContainer ($service)
	{
		return $this->serviceContainer->get($service);
	}

	/**
	 * 发生错误处理
	 * @param  string $message 
	 * @param  string $file    
	 * @param  integer $line 
	 * @param  array $appends 
	 * @return void
	 */
	public static function termination ($message = '',$file = '',$line = '',$level = 'error',$appends = [])
	{
        // 记录错误信息
        Log::write(sprintf("%s@%s %s",$message,$file,$line),$level,$appends);
        if (!self::$debug) {
        	$termination = config('error_handle.termination');
        	return $termination instanceof \Closure && $termination(['message' => $message,'file' => $file,'line' => $line]);
        }
	}
}