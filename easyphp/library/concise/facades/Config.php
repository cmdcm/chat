<?php
namespace concise\facades;
use concise\Facade;
class Config extends Facade
{
	public static function getFacadeAccessor ()
	{
		return "\\concise\\Config";
	}
}