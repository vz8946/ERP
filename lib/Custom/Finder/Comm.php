<?php

class Custom_Finder_Comm{

    protected $tbl_name;
    protected $colModel=array();
    protected $pk;
	protected $ord = '';
    
    public function __construct(){
        
        $this->colModel = $this->colModel();
        
        $tbl_name = get_class($this);
        $tbl_name = str_replace('Custom_Finder_', '', $tbl_name);
        $tbl_name = 'shop_'.strtolower($tbl_name);
        $this->tbl_name = $tbl_name; 
        $this->pk = $this->getPk();
        
    }
    
    public function getFilter(){
        
        $filter = array();
        
        foreach ($this->colModel as $k=>$v){
            if(!empty($v['filter']) && $v['filter'] == true) $filter[$v['field']] = $v['title'];
        }
        
        return $filter;
    }
    
    public function getColModel(){
        $colModel = $this->colModel();
        if(method_exists($this, 'singleActions')){
            array_unshift($colModel, array('field'=>'actions','title'=>'操作','width'=>100));
        }
        array_unshift($colModel, array('field'=>'ck','checkbox'=>true));
        
        return $colModel;
		
    }
    
    public function getAdvfilter(){
        $advFilter = array();
        foreach ($this->colModel as $k=>$v){
            if(!empty($v['advfilter'])){
                $v['advfilter']['title'] = $v['title'];
                $advFilter[$v['field']] = $v['advfilter'];
            }
        }
        
        return $advFilter;
    }
    
    public function colModel(){
        return array(
        );
    }
    
    public function where($request = array(),$ext_where = array()){
        
        $where = array();
        $advfilter = $this->getAdvfilter();
        
        if(!empty($request['qf_name'])){
            $where[$request['qf_name'].'|l'] = empty($request['qf_value']) ? '' : $request['qf_value'];
        }
        
        foreach ($advfilter as $k=>$v){
            if(!empty($request[$k])){
                if($v['searchtype'] == 'has'){
                    $where[$k.'|l'] = $request[$k]; 
                }else{
                    $where['`'.$k.'`'] = $request[$k]; 
                }
            }
        }
        
        return array_merge($where,$ext_where);
        
    }
    
    
    public function getGridData($request = array()){
        
        $colModel = $this->colModel();
        $arr_col = array();
        
        foreach ($colModel as $k=>$v){
            $arr_col[] = $v['field'];
        }
        
        $db_comm = new Admin_Models_DB_Comn();
        
        $page['pn'] = empty($request['page']) ? 1 : (int)$request['page'];
        $page['ps'] = empty($request['rows']) ? 20 : (int)$request['rows'];
        $arr_col = array_unique($arr_col);
        $list = $db_comm->getAjaxListByPage($page,$this->tbl_name.'|'.implode(',', $arr_col),array(),$this->where($request),$this->ord);
        
        $pk = $this->getPk();
        
        foreach ($list as $k=>$v){
            $list[$k]['ck'] = $v[$pk];
            if(method_exists($this, 'singleActions')){
                $list[$k]['actions'] = $this->getSingleActionsTpl($v);
            }
        }
        
        $data = array();
        $data['total'] = $page['tcount'];
		
		$this->modifyList($list);
		
		foreach($list as $k=>$v){
			$this->modifyRow($list[$k]);
		}
		
        $data['rows'] = $list;
        
        return $data;
    }

	public function modifyRow(&$r){
	}
	public function modifyList(&$list){
	}
        
    public function getSltListByIds($ids){
        
        $colModel = $this->colModel();
        $arr_col = array();
        
        foreach ($colModel as $k=>$v){
            $arr_col[] = $v['field'];
        }
        
        $db_comm = new Admin_Models_DB_Comn();
        $arr_col = array_unique($arr_col);
        $pk = $this->getPk();
        $list = $db_comm->getAll($this->tbl_name,array($pk.'|in'=>$ids),implode(',', $arr_col),0);
        
        return $list;
    }
    
    public function getPk(){
        foreach ($this->colModel as $k=>$v){
            if(!empty($v['pk']) && $v['pk'] == true) return $v['field'];
        }
        return '';
    }
    
    public function getTitle(){
        foreach ($this->colModel as $k=>$v){
            if(!empty($v['is_title']) && $v['is_title'] == true) return $v['field'];
        }
        return '';
    }
    
    public function getSingleActionsTpl($row){
        
        $actions = $this->singleActions($row);
        
        $tpl = '<div><a id="menubutton-grid-sa-handler-'.$row[$this->pk].'" href="javascript:void(0);">操作</a></div>';
        $tpl .= '<div id="menubutton-grid-sa-'.$row[$this->pk].'" style="width:150px;">';
        foreach ($actions as $k=>$v){
            
            $type = '';
            if(!empty($v['type']) && $v['type'] == 'del'){
                $tpl .= '<div><a class="btn-ajax-confirm" msg="确定要删除吗？" afterdo="grid_finder_sa_after" href="'.$v['href'].'">'.$v['label'].'</a></div>';
            }elseif(!empty($v['type']) && $v['type'] == 'winmodel'){
                $tpl .= '<div onclick="openWin(\''.$v['href'].'\',\''.$v['width'].'\',\''.$v['height'].'\');">'.$v['label'].'</div>';
            }elseif(!empty($v['type']) && $v['type'] == 'ajax'){
                $tpl .= '<div><a class="btn-ajax" href="'.$v['href'].'">'.$v['label'].'</a></div>';
            }
        }
        $tpl .= '</div>';
        $tpl .= '<script>$("#menubutton-grid-sa-handler-'.$row[$this->pk].'").menubutton({menu:"#menubutton-grid-sa-'.$row[$this->pk].'"});</script>';
        
        return $tpl;
        
    }
        
}