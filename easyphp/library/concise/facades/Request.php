<?php
namespace concise\facades;
use concise\Facade;
class Request extends Facade
{
	public static function getFacadeAccessor ()
	{
		return "\\concise\\Request";
	}
}