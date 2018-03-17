<?php
namespace app\providers;

class TestProvider
{
	protected $message;

	public function __construct ($message)
	{
		$this->message = $message;
	}

	public function getMessage ()
	{
		return $this->message;
	}

	public function printMessage ()
	{
		echo $this->message;
	}

	// 获取结束运行时
	public function getRuntime ($info = 'Runtime: ')
	{
		$end = microtime(true);
		return $info . round($end - EASY_PHP_START,3);
	}
}