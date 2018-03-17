<?php
namespace app\chat\controllers;
use app\models\Message;
use PHPSocketIO\SocketIO;
use Workerman\Worker;
class ServerController
{
	protected $io;

	// 初始化
	public function __construct ()
	{
		// 创建socket.io服务端，监听2021端口
		$this->io = new SocketIO(2021);
	}
	// 运行开始
	public function run ()
	{
		$io = $this->io;

		// 当有客户端连接时输出连接信息
		$io->on('connection', function($socket) use ($io) {
		    $socket->on('userGoLine',function ($msg) use ($io) {
		    	 echo "[user]" . $msg ." On the line~\r\n";
		    });
		    $socket->on('sendMessage',function ($data) use ($io) {
		    	$createTime = time();
		    	$message 	= sprintf("[%s] <span style='color:red'>%s</span> : %s<br/>\n",date('Y-m-d H:i:s',$createTime),$data['nickname'],$data['message']);
		    	print( sprintf("[%s] %s: %s",date('Y-m-d H:i:s',$createTime),$data['nickname'],$data['message']) );
		    	Message::insert(['nickname' => $data['nickname'],'content' => $data['message'],'create_time' => $createTime]);
		    	$io->emit('getMessage',$message);
		    });
		});
		Worker::runAll();
	}
}