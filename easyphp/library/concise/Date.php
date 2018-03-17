<?php
namespace concise;

class Date
{
	protected $dateTimeZone;

	// 构造函数初始化
	public function __construct ($dateTimeZone = 'PRC')
	{
		$this->setDateTimeZone($dateTimeZone);
	}

	/**
	 * 设置默认时区
	 * @param  string $dateTimeZone 
	 * @return object
	 */
	public function setDateTimeZone ($dateTimeZone = 'PRC')
	{
		$dateTimeZone 		= empty($dateTimeZone) ? 'PRC' : $dateTimeZone;
		$this->dateTimeZone = $dateTimeZone;
		date_default_timezone_set($this->dateTimeZone);
		return $this;
	}

	/**
	 * 获取设置默认时区
	 * @return string
	 */
	public function getDateTimeZone ()
	{
		return !empty($this->dateTimeZone) ? $this->dateTimeZone : date_default_timezone_get();
	}
}