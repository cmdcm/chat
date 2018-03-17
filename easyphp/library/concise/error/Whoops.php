<?php
/**
*  whoops组件
* ================================================
* Copy 2018
* Web: www.easyphp.com
* ================================================
* Author: Mdcm
* Date: 2018/3/4
*/
namespace concise\error;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
class Whoops implements ErrorHandleInterface
{
	/**
	 * 错误处理
	 * @param  string $title 
	 * @return bool
	 */
	public function handle ($title)
	{
   		$run 	= new Run();
        
        $option = new \Whoops\Handler\PrettyPageHandler();

        $option->setPageTitle($title);

		$run->pushHandler($option);

		if (\Whoops\Util\Misc::isAjaxRequest()) {
		    $jsonHandler = new JsonResponseHandler();
		    $run->pushHandler($jsonHandler);
		}

		$run->register();

		return true;
	}
}