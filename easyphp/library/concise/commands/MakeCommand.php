<?php
namespace concise\commands;
use concise\Console;
use concise\exception\ConsoleException;
class MakeCommand extends Console
{	
	/**
	 * 模板所在目录
	 * @var string
	 */
	const TEMPLATE_PATH = EASY_PHP_PATH . '/tpl/make';

	/**
	 * 默认后缀
	 * @var string
	 */
	const EXT = 'tpl';

	/**
	 * 附加属性
	 * @var array
	 */
	protected $attrs = [];

	/**
	 * 处理
	 * @return mixed
	 */
	public function handle ()
	{
		empty(config('namespace')) && config()->load(EASY_CONFIG_PATH . '/app.php');
		$method = array_keys($this->args)[0];
		if (method_exists($this,$method)) {
			return call_user_func_array([$this,$method],[$this->args[$method]]);
		}
		throw new ConsoleException($method . " is not exists!","not command");
	}
	/**
	 * 创建控制器模板
	 * @param  string $putPath 
	 * @return mixed
	 */
	public function controller ($putPath)
	{
		$path = $this->parsePath($putPath);
		return $this->createTemplate(sprintf("%s/controllers/%sController.php",$path['path'],$path['last']),'controllers',$path);
	}
	/**
	 * 创建模型模板
	 * @param  string $putPath 
	 * @return mixed
	 */
	public function model ($putPath)
	{
		$path = $this->parsePath($putPath);
		return $this->createTemplate(sprintf("models/%s.php",$path['last']),'models',$path);
	}
	/**
	 * 创建模板
	 * @param  string $type 
	 * @param  string $path 
	 * @param  array $options 
	 * @return mixed       
	 */
	protected function createTemplate ($path,$type = '',$options = [])
	{
		$namespace   = config('namespace');
		$putPath   	 = dirname(EASY_PHP_PATH) . '/' . $namespace . '/' . $path;
		$tplPath 	 = self::TEMPLATE_PATH . '/'. rtrim(ucfirst($type),'s') . '.' . self::EXT;
		if (!is_file($tplPath)) {
			throw new ConsoleException("template is not exists:" . $tplPath,$this->command);
		}
		$data = ['namespace' => sprintf("%s%s\%s;\r\n",$namespace,empty($options['path']) ? '' : '\\' . $options['path'],$type),'className' => $options['last']];
		ob_start();
		extract($data);
		include $tplPath;
		$content = ob_get_contents();
		$content = "<?php\r\n" . $content;
		ob_clean();
		if (is_file($putPath)) {
			throw new ConsoleException(sprintf("%s:%s is exists!",$options['last'],$type),$this->command);
		}
		if (!file_put_contents($putPath, $content)) {
			throw new ConsoleException(sprintf("%s:%s is create fail!",$options['last'],$type),$this->command);
		}
		return $this->out(sprintf("%s:%s template create success!",$options['last'],$type));
	}
	/**
	 * 路径处理
	 * @param  string $path 
	 * @return array  
	 */
	protected function parsePath ($path)
	{
		$paths 		= explode('/',$path);
		$lastPath = ucfirst(array_pop($paths));
		return ['last' => $lastPath,'path' => implode('/',$paths)];
	}
	/**
	 * 设置attr属性
	 * @return string
	 */
	protected function setAttrs ()
	{
		$attrs  = $this->args;
		array_shift($attrs);
		$result = '';
		foreach ($attrs as $k => $v) {
			$result .= sprintf("protected %s%s;\r\n",'$' . $k,empty($v) ? '' : " = '{$v}'");
		}
		return $result;
	}
}