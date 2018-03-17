<?php
namespace app\commands;
use concise\Console;
use concise\exception\ConsoleException;
class TestCommand extends Console
{
	public function handle ()
	{
		$this->out("test out~");
	}
}