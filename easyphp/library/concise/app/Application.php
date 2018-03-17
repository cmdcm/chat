<?php
namespace concise\app;
abstract class Application
{
	/**
	 * handleRequest
	 * @param  array $params
	 * @return miexd
	 */
	public abstract function handleRequest ($params);
}