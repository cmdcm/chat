<?php
namespace concise;
use concise\exception\HttpException;
class Router
{
	private $container;

	public function __construct ()
	{
		$this->container = \concise\Container::getInstance()->set([
			'path' 	   => '\concise\routers\Path',
			'ordinary' => '\concise\routers\OrdInAry',
			'request'  => '\concise\Request'
		]);
	}
	/**
	 * 路由调度
	 * @param  array $params
	 * @return string|array|object
	 */
	public function dispatcher ($params = [])
	{
		if (empty($params)) {
			$router  = $this->container->get(config()->get('drive','router'));
			$params  = $router->parse();
		}
		$request = $this->container->get('request');
		$request->set([
			'module' 	 => $params['module'],
			'controller' => $params['controller'],
			'action'	 => $params['method']
		])->params(isset($params['params']) ? $params['params'] : []);
		
		// 解析开始
		$namespace = config('namespace');
		$module    = str_replace("\\","/",sprintf("%s/%s",$request->getVar('appPath'),$params['module']));
		if (!is_dir($module)) {
			throw new HttpException(sprintf("%s module not exists!",$params['module']));
		}
		$controller = sprintf("%s/controllers/%sController.php",$module,$params['controller']);
		if (!is_file($controller)) {
			throw new HttpException(sprintf("%s controller not exists!",$params['controller']));
		}
		$className = sprintf("%s\\%s\\controllers\\%sController",$namespace,$params['module'],$params['controller']);
		
		$result    = Ioc::make($className,$params['method'],array_merge($request->params(),$request->get()));
		return $result;
	}
}