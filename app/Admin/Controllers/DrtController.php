<?php
/*
 * Created on 2013-5-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Admin_DrtController extends Custom_Controller_Action_Grid {
     
    /**
     * 
     * @var Admin_Models_API_Drt
     */ 
 	private $api  = null;
	
	public $mdl_name = 'drt';
 	
 	//初始化对象
 	public function init()
	{
	    Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	    
		$this ->api = new Admin_Models_API_Drt();
		$this->_pageSize = Zend_Registry::get('config')->view->page_size;
		
		$this->finder = new Custom_Finder_Drt();

		$this->view->params = $this->_request->getParams();
		
		$this->view->opt_enable = array('Y'=>'是','N'=>'否');
		
	}

    
    public function indexAction(){
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    }
    
    /**
     * 新的装修
     */
    public function addAction(){

        $this->view->params = $this->_request->getParams();
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
          $obj_file = new Custom_Model_File();
        $list_dir = $obj_file->dir_tree(SHOP_TPL_ROOT.'wdgtpl/');
		
		foreach ($list_dir as $k => $v) {
			require_once $v['dir'].'conf.php';
			$list_dir[$k]['title'] = empty($conf['title']) ? $v['name'] : $conf['title'];
		}
		
        $this->view->list_dir = $list_dir;
    }
    

    /**
     * 编辑装修视图
     */
    public function editAction(){

        $this->view->params = $this->_request->getParams();
        
        //模板列表
        $obj_file = new Custom_Model_File();
        $list_dir = $obj_file->dir_tree(SHOP_TPL_ROOT.'wdgtpl/');
		foreach ($list_dir as $k => $v) {
			require_once $v['dir'].'conf.php';
			$list_dir[$k]['title'] = empty($conf['title']) ? $v['name'] : $conf['title'];
		}
		
        $opt_dir = array();
        foreach ($list_dir as $k=>$v){
            $opt_dir[$v['name']] = $v['title'];                
        }
        
        $this->view->opt_tpl = $opt_dir;
        
        $id = $this->_request->getParam('id',0);
        
        $r_drt = $this->api->getById($id);
        $r_drt['datasource'] = unserialize($r_drt['datasource']);

        $editcopy = $this->_request->getParam('editcopy','');
        if(!empty($editcopy) && $editcopy == 'Y'){
            unset($r_drt['id']);
        }
		
        $this->view->r = $r_drt;
        
    }
    
    /**
     * 保存装修
     */
    public function addDoAction(){
        
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $api_drt = new Admin_Models_API_Drt();
        $params = $this->_request->getParams();
        
        //变量检测
        //装修标识不能为空且唯一
        if(empty($params['name'])) Custom_Model_Tools::ejd('fail','装修标识不能为空！');
        
        $name = $params['name'];
        if($api_drt->checkNameExsits($name,$this->_request->getParam('id',0))) Custom_Model_Tools::ejd('fail','相同的装修标识已存在！');

        //装修名称不能为空
        if(empty($params['title'])) Custom_Model_Tools::ejd('fail','装修名称不能为空！');
        
        //数据源不能为空
        if(empty($params['source'])) Custom_Model_Tools::ejd('fail','数据源不能为空！');
        
        //装修模板不能为空
        if(empty($params['tpl'])) Custom_Model_Tools::ejd('fail','装修模板不能为空！');
        
        //保存数据
        $data = $params;
        
        $source = $data['source'];
        
        $arr_data_key = array();
        
        if(!empty($source['value_data'])){
            $value_data = array();
            foreach ($source['value_data'] as $k=>$v){
                if(empty($v['name'])) continue;
                if(array_key_exists($v['name'], $value_data)) continue;
                $value_data[$v['name']] = empty($v['value']) ? '' : $v['value'];
                $arr_data_key[] = $v['name'];
            }
            $source['value_data'] = $value_data;
            
            if(!empty($value_data)){
                $source['value_data'] = $value_data;
            }else{
                unset($source['value_data']);
            }
            
        }
        
        if(!empty($source['line_data'])){
            $line_data = array();
            foreach ($source['line_data'] as $k=>$v){
                if(empty($v['name'])) continue;
                if(array_key_exists($v['name'], $line_data)) continue;
                $line_data[$v['name']] = $v;
                $arr_data_key[] = $v['name'];
            }
            $source['line_data'] = $line_data;
            
            if(!empty($line_data)){
                $source['line_data'] = $line_data;
            }else{
                unset($source['line_data']);
            }
            
        }
        
        //处理连接类型数据
        if(!empty($source['link_data'])){
            $link_data = array();
            foreach ($source['link_data'] as $k=>$v){
                if(empty($v['name'])) continue;
                if(in_array($v['name'], $arr_data_key)) continue;
                
                $name = '';
                foreach ($v as $kk=>$vv){
                    if($kk === 'name') $name = $vv;
                    break;
                }
                $arr_data_key[] = $name;

                foreach ($v as $kk=>$vv){
                    if($kk === 'name') continue;
                    
                    $vv['ord'] = intval($vv['ord']);
                    $link_data[$name][] = $vv;
                }
            }
            
            if(!empty($link_data)){
                $source['link_data'] = $link_data;
            }else{
                unset($source['link_data']);
            }
        }
        
        //处理品牌类型数据
        if(!empty($source['brand_data'])){
            $brand_data = array();
            foreach ($source['brand_data'] as $k=>$v){
                if(empty($v['name'])) continue;
                if(in_array($v['name'], $arr_data_key)) continue;
                
                $name = '';
                foreach ($v as $kk=>$vv){
                    if($kk === 'name') $name = $vv;
                    break;
                }
                
                $arr_data_key[] = $name;

                foreach ($v as $kk=>$vv){
                    if($kk === 'name') continue;
                    if(empty($vv['brand_id'])) continue;
                    $vv['ord'] = intval($vv['ord']);
                    $brand_data[$name][] = $vv;
                }
            }
            
            if(!empty($brand_data)){
                $source['brand_data'] = $brand_data;
            }else{
                unset($source['brand_data']);
            }
            
        }
        
        //处理商品类型数据
        if(!empty($source['goods_data'])){
            $goods_data = array();
            foreach ($source['goods_data'] as $k=>$v){
                if(empty($v['name'])) continue;
                if(in_array($v['name'], $arr_data_key)) continue;
                
                $name = '';
                foreach ($v as $kk=>$vv){
                    if($kk === 'name') $name = $vv;
                    break;
                }
                
                $arr_data_key[] = $name;

                foreach ($v as $kk=>$vv){
                    if($kk === 'name') continue;
                    if(empty($vv['goods_id'])) continue;
                    $vv['ord'] = intval($vv['ord']);
                    $goods_data[$name][] = $vv;
                }
            }
            
            if(!empty($goods_data)){
                $source['goods_data'] = $goods_data;
            }else{
                unset($source['goods_data']);
            }
            
        }
        
		
        //处理资讯类型数据
        if(!empty($source['news_data'])){
            $news_data = array();
            foreach ($source['news_data'] as $k=>$v){
                if(empty($v['name'])) continue;
                if(in_array($v['name'], $arr_data_key)) continue;
                
                $name = '';
                foreach ($v as $kk=>$vv){
                    if($kk === 'name') $name = $vv;
                    break;
                }
                
                $arr_data_key[] = $name;

                foreach ($v as $kk=>$vv){
                    if($kk === 'name') continue;
                    if(empty($vv['id'])) continue;
                    $vv['ord'] = intval($vv['ord']);
                    $news_data[$name][] = $vv;
                }
            }
            
            if(!empty($news_data)){
                $source['news_data'] = $news_data;
            }else{
                unset($source['news_data']);
            }
        }
		
        $data['datasource'] = serialize($source);
        
        $data['atime'] = mktime();
        
        if(!$rs = $api_drt->save($data)) Custom_Model_Tools::ejd('fail','保存失败！');;
        
        $id = is_array($rs) ? $rs['id'] : $rs;
            
        $this->refrashTplAction($id);

        $otype = $this->_request->getParam('otype','save-close');
        $ajax_back_status = $otype == 'save-close' ? 'succ-save-close' : 'succ';
        
        Custom_Model_Tools::ejd($ajax_back_status,'操作成功！');
        
    }
    
    /**
     * ajax加载值型数据
     */
    public function valueDataAction(){
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this->view->i =$this->_request->getParam('i',0);    
    }
    
    /**
     * ajax加载线型数据
     */
    public function lineDataAction(){
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this->view->i =$this->_request->getParam('i',0);    
    }
    
    /**
     * ajax加载连接类型数据
     */
    public function linkDataAction(){
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this->view->i =$this->_request->getParam('i',0);    
        
    }
    
    /**
     * ajax加载连接类型项目数据
     */
    public function linkSourceAddAction(){
        $this->view->i =$this->_request->getParam('i',0);    
        $this->view->j =$this->_request->getParam('j',0);    
    }
    
    /**
     * ajax 加载品牌项目数据
     */
    public function brandSourceAddAction(){
        
        $this->view->i =$this->_request->getParam('i',0);    
        $this->view->j =$this->_request->getParam('j',0);    
        
        $apiBrand = new Admin_Models_API_Brand();
        $list_brand = $apiBrand->getByIds($this->_request->getParam('ids',''));
        $this->view->list_brand = $list_brand;
    }
    
    /**
     * ajax 加载商品项目数据
     */
    public function goodsSourceAddAction(){
        
        $this->view->i =$this->_request->getParam('i',0);    
        $this->view->j =$this->_request->getParam('j',0);    
        
        $apiGoods = new Admin_Models_API_Goods();
        $list_goods = $apiGoods->getByIds($this->_request->getParam('ids',''));
		
        $this->view->list_goods = $list_goods;
    }
    
    /**
     * ajax 加载商品项目数据
     */
    public function newsSourceAddAction(){
        
        $this->view->i =$this->_request->getParam('i',0);    
        $this->view->j =$this->_request->getParam('j',0);    
        
        $apiNews = new Admin_Models_API_News();
        $list_news = $apiNews->getByIds($this->_request->getParam('ids',''));
        $this->view->list_news = $list_news;
        
    }
    
    /**
     * ajax 加载品牌类型数据
     */
    public function brandDataAction(){
        $this->view->i =$this->_request->getParam('i',0);
    }
    
    /**
     * ajax 加载商品类型数据
     */
    public function goodsDataAction(){
        $this->view->i =$this->_request->getParam('i',0);
    }
    
    /**
     * ajax 加载资讯类型数据
     */
    public function newsDataAction(){
        $this->view->i =$this->_request->getParam('i',0);
    }
    
    /**
     * 删除装修
     */
    public function delDoAction(){

        $id = $this->_request->getParam('id');
        $id = trim($id,',');
        
        //取得数据列表
        $list_drt = $this->api->getListByIds($id);
        
        //根据数据列表删除模板文件
        foreach ($list_drt as $k=>$v){
            if(@file_exists(SHOP_TPL_ROOT.'widget/'.$v['name'].'.tpl')){
                @unlink(SHOP_TPL_ROOT.'widget/'.$v['name'].'.tpl');
            }
        }
        
        //删除数据
        $this->api->del($id);
        
        Custom_Model_Tools::ejd('succ','操作成功！');
        
    }
    
    /**
     * 刷新模板
     * @param unknown_type $tplid
     */
    public function refrashTplAction($tplid =0){
        
        $id = $tplid>0 ? $tplid : $this->_request->getParam('id',0);

        $r_drt = $this->api->getById($id);
        
        $this->api->refresh($r_drt['name'],'file');
        
        if($tplid<=0){
            Custom_Model_Tools::ejd('succ','操作成功！');
        }
        
    }
    
    /**
     * 批量刷新模板
     */
    public function refrashBatchAction(){
        $list_drt = $this->api->getAll();
        foreach ($list_drt as $k=>$v){
            $this->refrashTplAction($v['id']);
        }
        
        Custom_Model_Tools::ejd('succ','操作成功！');
        
    }
    
    /**
     * 自定义排序
     * @param unknown_type $a
     * @param unknown_type $b
     * @return number
     */
    private function sortData($a,$b){
        if($a['ord'] == $b['ord']) return 0;
        return $a['ord'] > $b['ord'] ? -1 : 1;
    }
    
    /**
     * 自定义过滤不可用数据
     * @param unknown_type $v
     * @return boolean
     */
    private function filterData($v){
        if($v['enable'] == 'N') return false;
        return true;
    }

    
    
 }

