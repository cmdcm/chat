<?php
namespace concise;

class Input
{
   /**
    * 获取输入过滤数据
    * @param  array $data    
    * @param  string $name    
    * @param  string $default 
    * @return string         
    */
  	public static function get ($data = [],$name = '',$default = '')
  	{
  	     if (empty($data)) {
             return $default;
         }
         $filterData = self::filterData($data);
         if (empty($name)) {
           return $data;
         }
         if (!strpos($name,'.')) {
             return isset($filterData[$name]) ? $filterData[$name] : $default;
         }
         $args = explode('.',$name); // 最高可获取二维数组值

         return isset($filterData[$args[0]][$args[1]]) ? $filterData[$args[0]][$args[1]] : $default;
  	}
    /**
     * 设置值
     * @param array $data  
     * @param stromg $name  
     * @param string $value
     * @return bool 
     */
    public static function set (&$data,$name,$value = '')
    {
        $value = self::filterData($value);
        if (!strpos($name,'.')) {
            $data[$name] = $value;
        } else {
            $args = explode('.',$name);
            if (!is_array($data[$args[0]])) {
                $newData = empty($data[$args[0]]) ? [] : [$args[0] => $data[$args[0]]];
                $data[$args[0]] = $newData;
            }
            $data[$args[0]][$args[1]] = $value;
        }
        return true;
    }
    /**
     * 删除值
     * @param  array &$data 
     * @param  string $name  
     * @return bool        
     */
    public static function delete (&$data,$name)
    {
        $data = self::filterData($data);
        if (!strpos($name,',')) {
            if(isset($data[$name])) unset($data[$name]);
        } else{
           $args = explode('.',$name);
           if(isset($data[$args[0]][$args[1]])) unset($data[$args[0]][$args[1]]);
        }
        return true;
    }
  	/**
     * php防注入和xss攻击通用过滤
     * @param  string|array|object $data 需要过滤字符串数组或对象
     * @return string
     */
    public static function filterData ($data = [])
    {
        $result = '';
        if (is_string($data)) {
            if (!get_magic_quotes_gpc()) $data = addslashes($data);
            return htmlspecialchars($data);
        }
        if (is_array($data)) {
           foreach ($data as $k => $v) {
               $result[$k] = self::filterData($v);
           }
        } else if (is_object($data)) {
           foreach ($data as $k => $v) {
               $data->$k = self::filterData($v);
           }
        } else {
          if (!get_magic_quotes_gpc()) $result = addslashes($data);
          $result = htmlspecialchars($result);
        }
        return $result;
    }
}