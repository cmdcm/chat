<?php
/**
*  自动依赖注入
* ================================================
* Copy 2018
* Web: www.easyphp.com
* ================================================
* Author: Mdcm
* Date: 2018/3/4
*/
namespace concise;

class Ioc
{	

	/**
	 * 获取类对象实例
	 * @param  string $className 
     * @param  array $params  
	 * @return object
	 */
	public static function getInstance ($className,$params = [])
	{
		$paramArr = self::getMethodParams($className,'__construct',$params);

        return (new \ReflectionClass($className))->newInstanceArgs($paramArr);
	}

	/**
	 * 执行类方法
	 * @param  string $className   
	 * @param  string $methodsName 
	 * @param  array $params 
	 * @return string|array|object            
	 */
	public static function make ($className,$methodsName,$params = []) {
		 // 获取类的实例
        $instance = self::getInstance($className);

        // 获取该方法所需要依赖注入的参数
        $paramArr = self::getMethodParams($className, $methodsName,$params);
        if (!method_exists($instance,$methodsName)) {
        	throw new \RuntimeException(sprintf("%s 中%s方法不存在",$className,$methodsName));
        }
        return call_user_func_array([$instance,$methodsName],$paramArr);
	}
	/**
	 * 获取类的方法参数
	 * @param  string $className  
	 * @param  string $methodsName 
	 * @param  array $arguments 
	 * @return array   
	 */
	protected static function getMethodParams ($className,$methodsName = '__construct',$arguments = [])
	{
		// 通过反射获得该类
        $class = new \ReflectionClass($className);
        $paramArr = []; // 记录参数，和参数类型

        // 服务容器
        $container = Container::getInstance();
                
        // 判断该类是否有构造函数
        if ($class->hasMethod($methodsName)) {
            // 获得构造函数
            $construct = $class->getMethod($methodsName);

            // 判断构造函数是否有参数
            $params = $construct->getParameters();

            if (count($params) > 0) {

                // 判断参数类型
                foreach ($params as $key => $param) {

                    if ($paramClass = $param->getClass()) {
                        // 获得参数类型名称
                        $paramClassName = $paramClass->getName();
                        // 获得参数类型
                        $paramsClassNames = explode("\\",$paramClass->getName());
                        $paramsClassName  = lcfirst($paramsClassNames[count($paramsClassNames) - 1]);
                        $args             = self::getMethodParams($paramClassName);

                        $paramArr[] = !empty($container->get($paramsClassName)) 
                        			  ? $container->get($paramsClassName)
                        			  : (new \ReflectionClass($paramClass->getName()))->newInstanceArgs($args);

                    }
                    if ($paramsName = $param->getName()) {
                    	if (isset($arguments[$paramsName])) {
                    		$paramArr[] = $arguments[$paramsName];
                    	} elseif ($param->isDefaultValueAvailable()) {
                    		$paramArr[] = $param->getDefaultValue();
                    	}
                    }
                }
            }
        }

        return $paramArr;
	}
};