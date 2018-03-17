<?php
namespace concise\facades;
use concise\Facade;
class Log extends Facade
{
	public static function getFacadeAccessor ()
	{
		return "\\concise\\Log";
	}
}