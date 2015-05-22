<?php
class Purchase_Models_DB_Page
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	private $_db = null;
	/**
     * page size
     * @var    int
     */
	private $_pageSize = null;
	/**
     * table name
     * @var    string
     */
	private $_table = 'shop_article';
	private $_table_article_cat = 'shop_article_cat';
	private $_table_act = 'shop_activity';
	private $table_msg = 'shop_msg';
	private $table_complaint = 'shop_complaint';
    private $table_topics = 'shop_topics';


	/**
     * 专题根据id获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getTopsById($id = null)
	{
        if($id>=1){
          return  $this -> _db -> fetchRow("SELECT * FROM $this->table_topics where  id= $id");
        }
	}

	/*读出符合要求的专题*/
	public function getTopics(){
		 return  $this -> _db -> fetchAll("SELECT id,name,imgUrl FROM $this->table_topics where isDisplay = 1 order by id desc,grade desc,sort desc ");
	}


	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}
	/**
     * 获取数据
     *
     * @param    string      $where
     * @param    string      $fileds
     * @return   array
     */
	public function getInfo($where, $fileds = '*')
	{
		$sql = "SELECT $fileds FROM `$this->_table` a LEFT JOIN `$this->_table_article_cat` b ON a.cat_id=b.cat_id WHERE $where ORDER BY a.sort";
		return $this -> _db -> fetchAll($sql);
	}
	/**
     * 获取分类列表
     *
     * @param    string   $where
     * @return   array
     */
	public function getCat($where = null)
	{
		$where && $whereSql = "$where";
		$sql = "SELECT * FROM `$this->_table_article_cat` where $whereSql ORDER BY sort,cat_id";
		return $this -> _db -> fetchAll($sql);
	}

    /**
     * 取得优惠活动记录
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getActList($where= null, $page)
    {
		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where) && count($where) ) {
                foreach ($where as $key => $value)
                {
                    $whereSql .= " AND $key='$value'";
                }
			}
		}
        $sql = " FROM ".$this -> _table_act." {$whereSql} ";
        $sqlOfList = "SELECT * {$sql} order by act_id desc LIMIT {$this -> _pageSize} OFFSET " . (($page - 1) * $this -> _pageSize);
        $sqlOfTotal = "SELECT count(*) {$sql}";
        $data['data'] = $this -> _db -> fetchAll($sqlOfList);
        $data['total'] = $this -> _db -> fetchOne($sqlOfTotal);
        return $data;
    }

    /**
     * 得到某张表
     *
     * @param string $table
     * @param string $fields
     * @param string/array $search
     * @param int $page
     * @param int $pageSize
     * @param string $orderBy
     *
     * @return array
     */
    public function getTable($search=null, $fields='*', $page=null, $pageSize=50, $orderBy=null) {
        $limit = null;
        if ($page != null) {
            $offset = ($page-1)*$pageSize;
            $limit = " LIMIT  $pageSize  OFFSET $offset";
        }
        $where = ' where 1 ';
        if(is_string($search) && trim($search)){
            $where .= 'and '.$search;
        }elseif(is_array($search) && count($search)){
            foreach ($search as $k=>$v){
                $where .= " and ".$k."='".$v."'";
            }
        }
        if($orderBy){
            $orderBy = "order by ".$orderBy;
        }
        $rs = array();
        $rs['tot'] = $this -> _db -> fetchOne("select count(*) from {$this -> table_complaint} $where");
        $rs['datas'] = $this -> _db -> fetchAll("select $fields from {$this -> table_complaint} $where $orderBy $limit");
        return $rs;
    }
}