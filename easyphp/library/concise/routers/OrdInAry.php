<?php
namespace concise\routers;

class OrdInAry implements RouterInterface
{
	/**
	 * 解析路由
	 * @return array
	 */
	public function parse ()
	{
		$get = $_GET;
		$routeParams   = [];
		$isMultiModule = config()->get('multi_module','router');
		$parse = config()->get('parse','router');
		if ($isMultiModule) {
			$routeParams['module'] 	   = isset($get[$parse['module']]) && !empty($get[$parse['module']]) 
									 ? $get[$parse['module']] : config()->get('default_module','router');
			$routeParams['controller'] = isset($get[$parse['controller']]) && !empty($get[$parse['controller']]) 
									 ? $get[$parse['controller']] : config()->get('default_controller','router');
			$routeParams['method'] 	   = isset($get[$parse['method']]) && !empty($get[$parse['method']]) 
									 ? $get[$parse['method']] : config()->get('default_method','router');
		} else {
			$routeParams['module'] 	   = config()->get('default_module','router');
			$routeParams['controller'] = isset($get[$parse['controller']]) && !empty($get[$parse['controller']]) 
									 ? $get[$parse['controller']] : config()->get('default_controller','router');
			$routeParams['method'] 	   = isset($get[$parse['method']]) && !empty($get[$parse['method']]) 
									 ? $get[$parse['method']] : config()->get('default_method','router');
		}
		$routeParams['module'] 		   = lcfirst($routeParams['module']);
		$routeParams['controller']	   = ucfirst($routeParams['controller']);
		$routeParams['method']		   = lcfirst($routeParams['method']);

		$routeParams['params'] = [];
		return $routeParams;
	}
}