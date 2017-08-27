<?php
/**
 * 分类处理
 */
 
class CategoryInfo
{
	/**
	 * 应用程序对象
	 * @var Application
	 */
	private $app = null;
	
	/**
	 * 数据库操作对象
	 * @var OrmQuery
	 */
	private $db = null;
	
	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
        $this->app = $app;
        $this->db = $app->orm($app->cfg['db'])->query();

        mysql_query("set names utf8");
    }
    
    //获取分类列表
    public function get_categoty_list()
    {
    	if($this->db == null)
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_category WHERE is_delete = 0 ORDER BY sort ASC";
    	
    	$r = $this->db->getArray($sql);
    	
    	return $r;
    }
    
    //获取分类详情
    public function get_category_info($cid=0)
    {
    	if($this->db == null)
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_category WHERE cid = {$cid}";
    	
    	$r = $this->db->getRow($sql);
    	
    	return $r;
    }
	

	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/Category")->setData($data)->write();
	    
	    return false;
	}
}
?>