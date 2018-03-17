<?php
namespace concise;
use concise\exception\ConsoleException;
class Console
{
	/**
	 * 输入的命令参数
	 * @var array
	 */
	protected $args = [];

	/**
	 * 当前执行命令
	 * @var string
	 */
	protected $command;

	// 构造方法初始化
	public function __construct ($argv = [])
	{
		$this->args = $argv;
		$this->command = isset($this->args['command']) ? $this->args['command'] : '';
		unset($this->args['command']);
	}

	/**
	 * make
	 * @param  array $argv 
	 * @return object    
	 */
	public static function make ($argv = [])
	{
		$params = self::parseArgs($argv);
		config()->load(EASY_CONFIG_PATH . '/commands.php','commands');
		if (empty($params['command'])) {
			return new self($params);
		}
		$classCommand = config()->get($params['command'],'commands');
		if (empty($classCommand)) {
			throw new ConsoleException("The command is not configured, please check!",$params['command']);
		}
		if (class_exists($classCommand)) {
			return new $classCommand($params);
		}
		throw new ConsoleException("command is not exists!",$classCommand);
	}

	/**
     *  解析控制台传递参数
     * @param  array $argv
     * @return array
     */
    protected static function parseArgs ($argv)
	{    
		$params = [];
		array_shift($argv);
		$command = array_shift($argv);
		$params['command'] = $command;
		
		array_walk($argv, function ($item) use (&$params) {
			if (strpos($item,':') !== false) {
				$items = explode(':',$item);
			} else {
				$items = explode('=',$item);
			}
			$params[isset($items[0]) ? $items[0] : ''] = isset($items[1]) ? $items[1] : '';
		});

		return $params;
	}
	/**
	 * 处理
	 * @return void
	 */
	public function handle ()
	{
		throw new ConsoleException("Console->handle is not rewrite!","not command");
	}
	/**
     * 输出调试信息
     * @param string $message 
     * @return object
     */
	public function out ($message)
	{ 
		is_array($message) ? print_r($message) : print($message);
		return $this;
	}
}