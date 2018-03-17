<?php
namespace concise;

class Request
{
    /**
     * 请求方法
     * @var string
     */
	protected $method; 
    /**
     * get请求数据存储
     * @var array
     */
    private $get = [];
    /**
     * post请求数据存储
     * @var array
     */
    private $post = [];
    /**
     * server变量数据存储
     * @var array
     */
    private $server = [];

    /**
     * 当前请求模块
     * @var string
     */
    private $module;
    /**
     * 当前请求控制器
     * @var string
     */
    private $controller;
    /**
     * 当前请求执行方法
     * @var string
     */
    private $action;

    /**
     * 路径路由解析数据存储
     * @var array
     */
    private $params = [];

    /**
     * 应用根目录
     * @var string
     */
    private $appPath;

    /**
     * ip地址
     * @var string
     */
    private $ip;

    /**
     * 当前操作系统
     * @var string
     */
    private $os;

    /**
     * 当前访问浏览器
     * @var string
     */
    private $browser;

    /**
     * 路由重定向
     * @param  string $url  URL地址
     * @param  string $time 跳转时间
     * @return void
     */
    public function redirect ($url,$time = '')
    {
         if ( !headers_sent() ) {
            empty($time) ? header('Location:' . $url) : header("refresh:{$time};url={$url}");;
         } else {
            echo "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
         }
         exit;
    }
    /**
     * 获取GET数据
     * @param  string 获取数据名称
     * @param  string 自定义数据
     * @param  string $default 默认值
     * @return string
     */
    public function get ($name = '',$data = [],$default = '')
    { 
        // 如果没传入名称返回过滤的$_GET
        if (empty($name)) {
            return !empty($_GET) ? $this->filterData($_GET) : [];
        }
        if (empty($data)) {
            $this->get = $_GET;
        } else {
            $this->get = array_merge($this->get,$data);
        }
        return $this->input($name,$this->get,$default);
    }
    /**
     * 获取POST数据
     * @param  string 获取数据名称
     * @param  string 自定义数据
     * @param  string $default 默认值
     * @return string
     */
    public function post ($name = '',$data = [],$default = '')
    {
        // 如果没传入名称返回过滤的$_POST
        if (empty($name)) {
            return !empty($_POST) ? $this->filterData($_POST) : [];
        }
        if (empty($data)) {
           $this->post = $_POST;
        } else {
           $this->post = array_merge($this->post,$data);
        }
        return $this->input($name,$this->post,$default);
    }
    /**
     * 获取 SERVER数据
     * @param  string $name    获取数据名称
     * @param  array $data     自定义数据
     * @param  string $default 默认值
     * @return string
     */
    public function server ($name = '',$data = [],$default = '')
    {
        // 如果没传入名称返回过滤的$_SERVER
        if (empty($name)) {
            return !empty($_SERVER) ? $this->filterData($_SERVER) : [];
        }
        if (empty($data)) {
            $this->server = $_SERVER;
        } else {
            $this->server = array_merge($this->server,$data);
        }
        return $this->input($name,$this->server,$default);
    }
    /**
     * 获取输入数据
     * @param  string 获取数据名称
     * @param  string|array 获取的数据值
     * @param  string $default 默认值
     * @return string
     */
    public function input ($name,$data = [],$default = '')
    {
        return Input::get($data,$name,$default);
    }
    /**
     * php防注入和xss攻击通用过滤
     * @param  string|array|object $data 需要过滤字符串数组或对象
     * @return string
     */
    public function filterData ($data = [])
    {
        return Input::filterData($data);
    }
    /**
     * 设置请求内部请求变量
     * @param array $options 
     * @return  object 
     */
    public function set ($options = [])
    {
        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
    /**
     * 获取内部变量
     * @param  string $key 
     * @return string|array|object    
     */
    public function getVar ($key)
    {
        return isset($this->$key) ? $this->$key : '';
    }
    /**
     * 获取|设置路径路由参数
     * @param  array $data 
     * @param  array $default 
     * @return array|object
     */
    public function params ($data = [],$default = '')
    {
        if (empty($data)) {
            return !empty($this->params) ? $this->filterData($this->params) : [];
        }
        if (is_array($data)) {
            $params = $this->parseParams($data);
            $this->params = array_merge($this->params,$params);
            return $this;
        }
        return $this->input($data,$this->params,$default);
    }
    /**
     * 解析路径路由参数
     * @param  array $data 
     * @return array
     */
    private function parseParams ($data = [])
    {
        $params = [];
        $data   = array_values($data);
        for ($i = 0; $i < count($data); $i += 2) { 
            if (isset($data[$i + 1])) {
                $params[$data[$i]] = $data[$i + 1];
            }
        }
        return $params;
    }
    /**
     * 获取当前请求模块
     * @return string
     */
    public function module ()
    {
        return $this->module;
    }
    /**
     * 获取当前控制器
     * @return string
     */
    public function controller ()
    {
        return $this->controller;
    }
    /**
     * 获取请求方法
     * @return string
     */
    public function action ()
    {
        return $this->action;
    }
    /**
     * 返回当前请求方法
     * @return string 
     */
	public function method ()
	{
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$this->method = "AJAX";
		} else {
			$this->method = $this->isCli() ? 'GET' : $_SERVER['REQUEST_METHOD'];
		}
		return $this->method;
	}
    /**
     * 是否为cli模式
     * @return boolean 
     */
    public function isCli ()
    {
        return PHP_SAPI == 'cli' ? true : false;
    }
    /**
     * 是否为get请求
     * @return boolean 
     */
	public function isGet ()
	{
		return $this->method() == 'GET';
	}
    /**
     * 是否为Post请求
     * @return boolean 
     */
	public function isPost ()
	{
		return $this->method() == 'POST';
	}
	/**
	 * 是否ajax请求
	 * @return boolean 
	 */
	public function isAjax ()
	{
        return $this->method() == 'AJAX';
	}

    /**
     * 获取ip地址
     * @return string
     */
    public function getIp ()
    {
        if (empty($this->ip)) {
             if (array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER) && $_SERVER["HTTP_X_FORWARDED_FOR"]) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (array_key_exists('HTTP_CLIENT_IP',$_SERVER) && $_SERVER["HTTP_CLIENT_IP"]) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } elseif ($_SERVER["REMOTE_ADDR"]) {
                $ip = $_SERVER["REMOTE_ADDR"];
            } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $ip = getenv("HTTP_CLIENT_IP");
            } elseif (getenv("REMOTE_ADDR")) {
                $ip = getenv("REMOTE_ADDR");
            } else {
                $ip = "Unknown";
            }
            if ( strpos($ip,',') && $iparr = explode(',',$ip) ) {
                $ip = $iparr[0];
            }
            $this->ip = $ip;
        }
        return $this->ip;
    }
    /**
     * 设置ip地址
     * @param string $ip 
     * @return object
     */
    public function setIp ($ip)
    {
        $this->ip = $ip;
        return $this;
    }
    /**
     * 获取操作系统
     * @return string
     */
    public function getOs ()
    {
        if (empty($this->os)) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/win/i', $agent) && strpos($agent, '95'))
            {
                $os = 'Windows 95';
            }
            else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90'))
            {
                $os = 'Windows ME';
            }
            else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent))
            {
                $os = 'Windows 98';
            }
            else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent))
            {
                $os = 'Windows Vista';
            }
            else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))
            {
                $os = 'Windows 7';
            }
            else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))
            {
                $os = 'Windows 8';
            }else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))
            {
                $os = 'Windows 10';#添加win10判断
            }else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))
            {
                $os = 'Windows XP';
            }
            else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent))
            {
                $os = 'Windows 2000';
            }
            else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent))
            {
                $os = 'Windows NT';
            }
            else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent))
            {
                $os = 'Windows 32';
            }
            else if (preg_match('/linux/i', $agent))
            {
                $os = 'Linux';
            }
            else if (preg_match('/unix/i', $agent))
            {
                $os = 'Unix';
            }
            else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))
            {
                $os = 'SunOS';
            }
            else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))
            {
                $os = 'IBM OS/2';
            }
            else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent))
            {
                $os = 'Macintosh';
            }
            else if (preg_match('/PowerPC/i', $agent))
            {
                $os = 'PowerPC';
            }
            else if (preg_match('/AIX/i', $agent))
            {
                $os = 'AIX';
            }
            else if (preg_match('/HPUX/i', $agent))
            {
                $os = 'HPUX';
            }
            else if (preg_match('/NetBSD/i', $agent))
            {
                $os = 'NetBSD';
            }
            else if (preg_match('/BSD/i', $agent))
            {
                $os = 'BSD';
            }
            else if (preg_match('/OSF1/i', $agent))
            {
                $os = 'OSF1';
            }
            else if (preg_match('/IRIX/i', $agent))
            {
                $os = 'IRIX';
            }
            else if (preg_match('/FreeBSD/i', $agent))
            {
                $os = 'FreeBSD';
            }
            else if (preg_match('/teleport/i', $agent))
            {
                $os = 'teleport';
            }
            else if (preg_match('/flashget/i', $agent))
            {
                $os = 'flashget';
            }
            else if (preg_match('/webzip/i', $agent))
            {
                $os = 'webzip';
            } else if (preg_match('/offline/i', $agent)) {
                $os = 'offline';
            } else {
                $os = '未知操作系统';
            }
            $this->os = $os;
        }
        return $this->os;
    }
    /**
     * 设置当前操作系统
     * @param string $os 
     * @return object
     */
    public function setOs ($os)
    {
        $this->os = $os;
        return $this;
    }
    /**
     * 获取当前浏览器
     * @return string
     */
    public function getBrowser ()
    {
        if (empty($this->browser)) {
            $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
            if (stripos($sys, "Firefox/") > 0) {
                preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
                $exp[0] = "Firefox";
                $exp[1] = $b[1];  //获取火狐浏览器的版本号
            } elseif (stripos($sys, "Maxthon") > 0) {
                preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
                $exp[0] = "傲游";
                $exp[1] = $aoyou[1];
            } elseif (stripos($sys, "MSIE") > 0) {
                preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
                $exp[0] = "IE";
                $exp[1] = $ie[1];  //获取IE的版本号
            } elseif (stripos($sys, "OPR") > 0) {
                preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
                $exp[0] = "Opera";
                $exp[1] = $opera[1];
            } elseif(stripos($sys, "Edge") > 0) {
                //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
                preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);
                $exp[0] = "Edge";
                $exp[1] = $Edge[1];
            } elseif (stripos($sys, "Chrome") > 0) {
                preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
                $exp[0] = "Chrome";
                $exp[1] = $google[1];  //获取google chrome的版本号
            } elseif(stripos($sys,'rv:')>0 && stripos($sys,'Gecko')>0){
                preg_match("/rv:([\d\.]+)/", $sys, $IE);
                $exp[0] = "IE";
                $exp[1] = $IE[1];
            } else {
                $exp[0] = "未知浏览器";
                $exp[1] = "";
            }
            $this->browser = $exp[0].'('.$exp[1].')';
        }
        return $this->browser;
    }
    /**
     * 设置当前浏览器
     * @param string $browser 
     * @return object
     */
    public function setBrowser ($browser)
    {
        $this->browser = $browser;
        return $this;
    }
    /**
     * 是否在移动端
     * @return boolean 
     */
    public function isMobile ()
    {
          // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
          if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
             return true;
          } 
          // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
          if (isset($_SERVER['HTTP_VIA'])) { 
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
          } 
          // 判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
          if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger'); 
                // 从HTTP_USER_AGENT中查找手机浏览器的关键字
                if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                    return true;
                } 
          } 
          // 协议法，因为有可能不准确，放到最后判断
          if (isset ($_SERVER['HTTP_ACCEPT'])) { 
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || ( strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                 return true;
            } 
          } 
          return false;
    }
    /**
     * 是否在微信内置浏览器
     * @return boolean 
     */
    public function isWeChatBrowser ()
    {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ? true : false;
    }
}