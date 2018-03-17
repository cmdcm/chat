<?php
namespace concise\session;

interface SessionInterface
{
	public function open ($path,$name);
	public function write ($sessionId,$data);
	public function read ($sessionId);
	public function destory ($sessionId);
	public function close();
	public function gc ($lifeTime);
}