<?php
namespace concise\routers;

class Path implements RouterInterface
{
	/**
	 * 解析路由
	 * @return array
	 */
	public function parse ()
	{
		$server = $_SERVER;
		$params = isset($server['PHP_INFO']) ? $server['PHP_INFO'] : $server['REQUEST_URI'];
		if (strpos('?',$params)) {
			$params = explode('?', $params)[0];
		}
		$params 	   = explode('/',trim($params,'/'));
		$routeParams   = [];
		$isMultiModule = config()->get('multi_module','router');
		$count 		   = $isMultiModule ? 3 : 2;
		if ($isMultiModule) {
			$routeParams['module']      = isset($params[0]) && !empty($params[0]) ? $params[0] : config()->get('default_module','router');
			$routeParams['controller']  = isset($params[1]) && !empty($params[1]) ? $params[1] : config()->get('default_controller','router');
			$routeParams['method']      = isset($params[2]) && !empty($params[2]) ? $params[2] : config()->get('default_method','router');
		} else {
			$routeParams['module'] 		= config()->get('default_module','router');
			$routeParams['controller']	= isset($params[0]) && !empty($params[0]) ? $params[0] : config()->get('default_controller','router');
			$routeParams['method']		= isset($params[1]) && !empty($params[1]) ? $params[1] : config()->get('default_method','router');
		}
		$routeParams['module'] 			= lcfirst($routeParams['module']);
		$routeParams['controller']		= ucfirst($routeParams['controller']);
		$routeParams['method']			= lcfirst($routeParams['method']);

		for ($i = 0; $i < $count; $i++) { 
			if (isset($params[$i])) {
				unset($params[$i]);
			}
		}
		$routeParams['params'] = $params;
		
		return $routeParams;
	}
}