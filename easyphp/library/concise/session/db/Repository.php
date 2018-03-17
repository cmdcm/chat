<?php
/*
 create table session_data (
  session_id varchar(40) not null default '',
  session_data text,
  update_time int(10) unsigned not null default 0,
  primary key(session_id)
);
 */
namespace concise\session\db;
use concise\session\SessionInterface;
use concise\Session;
class Repository extends Session implements SessionInterface
{
  /**
   * 数据库操作模型
   * @var object
   */
    protected $model;
    /**
     * 过期时间
     * @var integer
     */
    protected $lifeTime;
    /**
     * 修改时间
     * @var integer
     */
    protected $updateTime;
    // 构造方法初始化
  	public function __construct ()
  	{
          parent::__construct();
          session_set_save_handler(
              array(&$this,'open'), 
              array(&$this,'close'), 
              array(&$this,'read'),
              array(&$this,'write'),
              array(&$this,'destory'),
              array(&$this,'gc') 
          );
          $this->model      = new SessionDatabaseModel($this->config['table']);
          $this->lifeTime   = empty($this->config['life_time']) ? ini_get('session.gc_maxlifetime') : $this->config['life_time'];
          $this->updateTime = time();
  	}
    /**
     * 打开
     * @param  string $path 
     * @param  string $name 
     * @return bool       
     */
    public function open ($path,$name)
    {
        return true;
    }
    /**
     * 写入
     * @param  integer $sessionId 
     * @param  mixed $data      
     * @return mixed            
     */
    public function write ($sessionId,$data)
    {
        $result = $this->model->where('session_id',$sessionId)->first();
        if ( is_object($result) && !is_null($result) ) {
            if ($result->data  != $data || $this->updateTime > $result->update_time + 30) {
               return $this->model->where('session_id',$sessionId)->update(['update_time' => $this->updateTime,'session_data' => $data]);
            }
        } 
        if ( !empty($data) ) {
            return $this->model->insert([
                            'session_id'   => $sessionId,
                            'update_time'  => $this->updateTime,
                            'session_data' => $data]);
        }
    }
    /**
     * 读取
     * @param  integer $sessionId 
     * @return string
     */
    public function read ($sessionId)
    {
         $result = $this->model->where('session_id',$sessionId)->first();
         if ( !is_object($result) || is_null($result) ) {
             return '';
         }
         if ($result->update_time + $this->lifeTime < $this->updateTime) {
            $this->destory($sessionId);
            return '';
         }
         return $result->session_data;
    }
    /**
     * 删除
     * @param  integer $sessionId 
     * @return mixed         
     */
    public function destory ($sessionId)
    {
         return $this->model->where('session_id',$sessionId)->delete();
    }
    /**
     * 关闭
     * @return bool
     */
    public function close ()
    {
       return true;
    }
    /**
     * 垃圾回收机制
     * @param  integer $lifeTime 
     * @return mixed           
     */
    public function gc ($lifeTime)
    {
        $time   = $this->updateTime - $lifeTime;
        $result = $this->model->where('update_time','<',$time);
        return $result;
    }
}