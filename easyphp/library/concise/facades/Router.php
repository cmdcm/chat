<?php
namespace concise\facades;
use concise\Facade;
class Router extends Facade
{
	public static function getFacadeAccessor ()
	{
		return "\\concise\Router";
	}
}