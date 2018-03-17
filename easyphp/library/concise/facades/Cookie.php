<?php
namespace concise\facades;
use concise\Facade;
class Cookie extends Facade
{
	public static function getFacadeAccessor ()
	{
		return "\\concise\\Cookie";
	}
}