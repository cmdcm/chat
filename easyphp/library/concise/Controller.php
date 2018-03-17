<?php
namespace concise;

class Controller 
{
	public function __get ($name)
	{
		return Container::getInstance()->get($name);
	}
}