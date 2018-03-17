<?php
namespace concise\response;
use concise\Response;
class Json extends Response
{
	protected $contentType = 'application/json';

	/**
	 * 输出json格式数据
	 * @param  array $data 
	 * @return mixed
	 */
	public function output ($data)
	{
		try {
			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			if (false === $data) {
				throw new \InvalidArgumentException(json_last_error_msg());
			}
			return $data;
		} catch (\Exception $e) {
			throw $e;
		}
	}
}