<?php
/**
*  错误捕获处理
* ================================================
* Copy 2018
* Web: www.easyphp.com
* ================================================
* Author: Mdcm
* Date: 2018/3/4
*/
namespace concise;


class Error
{
	/**
	 * 注册错误处理
	 * @param bool $debug
	 * @return mixed
	 */
	public static function register ()
	{
		$container = Container::getInstance()->bind([
			'whoops' => function () {
				return new \concise\error\Whoops;
			}
		]);
		return error_reporting(E_ALL) && $container->make(config('error_handle.drive'))->handle(config('error_handle.title'));
	}
}