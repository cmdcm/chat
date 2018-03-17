<?php
namespace concise;


class Response
{
	/**
	 * 响应头部信息
	 * @var array
	 */
	protected $header = [];
	/**
	 * 数据
	 * @var array
	 */
	protected $data   = [];
	/**
	 * 状态码
	 * @var integer
	 */
	protected $code   = 200;
	/**
	 * contentType
	 * @var string
	 */
	protected $contentType = 'text/html';
	/**
	 * 输出编码
	 * @var string
	 */
	protected $charset = 'utf-8';

	/**
	 * 输出内容
	 * @var string
	 */
	protected $content;


	// 构造函数初始化
	public function __construct ($data = '',$code = 200,$header = [])
	{
		$this->data($data);

		$this->contentType($this->contentType,$this->charset);

		$this->code = $code;

		if (!empty($header)) {
			$this->header = array_merge($this->header,$header);
		}
	}

	/**
	 * 创建Response对象
	 * @param  array  $data   
	 * @param  string  $type   
	 * @param  integer $code   
	 * @param  array  $header 
	 * @return object          
	 */
	public static function create ($data = [],$type = '',$code = 200,$header = [])
	{
		$class = empty($type) ? '' : "\\concise\\response\\" . ucfirst($type); 
		if (class_exists($class)) {
			return new $class($data,$code,$header);
		} else {
			return new static($data,$code,$header);
		}
	}
	/**
	 * 发送数据
	 * @return void
	 */
	public function send ()
	{
		$content = $this->getContent();

		if (!headers_sent() && !empty($this->header)) {
			http_response_code($this->code);
			foreach ($this->header as $k => $v) {
				header($k . (!is_null($v) ? ':' . $v : ''));
			}
		}
		echo $content;
		if (function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
		}
	}

	/**
	 * 设置头部信息
	 * @param array $header 
	 * @return object
	 */
	public function setHeader ($name,$value = '')
	{
		if (!empty($value)) {
			$this->header[$name] = $value;
		} else {
			$this->header = array_merge($this->header,$name);
		}
		return $this;
	}

	/**
	 * 设置页面输出类型
	 * @param string $contentType
	 * @param string $charset    
	 * @return object
	 */
	public function contentType ($contentType,$charset = 'utf-8')
	{
		$this->charset 		= $charset ? $charset : $this->charset;
		$this->contentType  = $contentType ? $contentType : $this->contentType;
		$this->header['Content-Type'] = $this->contentType . ';' . 'charset=' . $this->charset;
		return $this;
	}
	/**
	 * 设置data
	 * @return object
	 */
	public function data ($data)
	{
		$this->data = $data;
		return $this;
	}
	/**
	 * 返回处理完数据
	 * @param  array $data [description]
	 * @return [type]       [description]
	 */
	public function output ($data)
	{
		return $data;
	}
	/**
	 * 返回内容
	 * @return string
	 */
	public function getContent ()
	{
		$content = $this->output($this->data);
		if ($content !== null && !is_string($content) && !is_numeric($content) && !is_callable([$content,'__toString'])) {
			throw new \InvalidArgumentException(sprintf('variable type error： %s', gettype($content)));
		}
		$this->content = (string)$content;
		return $this->content;
	}
}