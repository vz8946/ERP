<?php
 class Shop_Models_API_Newsadmin extends Custom_Model_Dbadv {
    	
	private $conf_view = array('Y'=>'可见','N'=>'不可见');	
	private $conf_article_status = array('AUDITED'=>'已审核','PENDING'=>'待审核');	
	private $conf_y = array('Y'=>'是','N'=>'否');	
	private $list_cat;
    public function __construct() {
    	parent::__construct();
		$this->list_cat = $this->getAll('shop_seo_cat');
    }
	
	public function getTreeFinderList(){
		
        $list = $this->getAll('shop_seo_cat',array('is_del'=>'N'));

        foreach ($list as $k=>$v){
            if(!empty($v['parent_id'])) $list[$k]['_parentId'] = $v['parent_id'];
			$list[$k]['__opt'] = $this->getCatItemOpt($v);
        }
		
        return array('total'=>count($list),'rows'=>$list); 
	}	
	
	public function getFinderList($p,$ext_where = array()){
		
		$page = array();
		$page['ps'] = empty($p['rows']) ? 10 : intval($p['rows']);
		$page['pn'] = empty($p['page']) ? 1 : intval($p['page']);
		$where = array();
		$where['is_del'] = 'N';
		if(!empty($p['qs_name']) && !empty($p['qs_value'])){
			$where[$p['qs_name'].'|l'] = $p['qs_value'];
		}		
		
		if(!empty($p['title'])){
			$where['title|l'] = $p['title'];
		}
		
		if(!empty($p['cat_id'])){
			$where['cat_id|in'] = Custom_Model_Tools::getCatSubId($this->list_cat, $p['cat_id']);
		}
		
		if(!empty($p['is_view'])){
			$where['is_view'] = $p['is_view'];
		}
		if(!empty($p['status'])){
			$where['status'] = $p['status'];
		}
		
		if(!empty($p['author'])){
			$where['author'] = $p['author'];
		}
		
		if(!empty($p['fatime']) && !empty($p['tatime'])){
			$where['add_time|egt'] = strtotime($p['fatime']);
			$where['add_time|elt'] = strtotime($p['tatime'])+60;
		}elseif(!empty($p['fatime']) && empty($p['tatime'])){
			$where['add_time|egt'] = strtotime($p['fatime']);
		}elseif(empty($p['fatime']) && !empty($p['tatime'])){
			$where['add_time|elt'] = strtotime($p['tatime'])+60;
		}
		$where = array_merge($where,$ext_where);
		
		$tbl = 'shop_seo_article|article_id,cat_id,title,add_time,sort,author,is_view,status';
        $list = $this->getAjaxListByPage($page,$tbl,array(),$where,'article_id desc');
		
        foreach ($list as $k=>$v){
        	$this->finder_list_modify($list[$k]);
        }
		
        return array('total'=>$page['tcount'],'rows'=>$list); 
	}	
	
	public function getRecycleFinderList($p,$ext_where = array()){
		
		$page = array();
		$page['ps'] = empty($p['rows']) ? 10 : intval($p['rows']);
		$page['pn'] = empty($p['page']) ? 1 : intval($p['page']);
		$where = array();
		$where['is_del'] = 'Y';
		if(!empty($p['qs_name']) && !empty($p['qs_value'])){
			$where[$p['qs_name'].'|l'] = $p['qs_value'];
		}		
		
		if(!empty($p['title'])){
			$where['title|l'] = $p['title'];
		}
		
		if(!empty($p['cat_id'])){
			$where['cat_id|in'] = Custom_Model_Tools::getCatSubId($this->list_cat, $p['cat_id']);
		}
		
		if(!empty($p['is_view'])){
			$where['is_view'] = $p['is_view'];
		}
		if(!empty($p['status'])){
			$where['status'] = $p['status'];
		}
		
		if(!empty($p['author'])){
			$where['author'] = $p['author'];
		}
		
		if(!empty($p['fatime']) && !empty($p['tatime'])){
			$where['add_time|egt'] = strtotime($p['fatime']);
			$where['add_time|elt'] = strtotime($p['tatime'])+60;
		}elseif(!empty($p['fatime']) && empty($p['tatime'])){
			$where['add_time|egt'] = strtotime($p['fatime']);
		}elseif(empty($p['fatime']) && !empty($p['tatime'])){
			$where['add_time|elt'] = strtotime($p['tatime'])+60;
		}
		$where = array_merge($where,$ext_where);
		
		$tbl = 'shop_seo_article|article_id,cat_id,title,add_time,sort,author,is_view,status';
        $list = $this->getAjaxListByPage($page,$tbl,array(),$where,'article_id desc');
		
        foreach ($list as $k=>$v){
        	$this->recycle_finder_list_modify($list[$k]);
        }
		
        return array('total'=>$page['tcount'],'rows'=>$list); 
	}	
	
	public function getFinderListTag($p){
		
		$page = array();
		$page['ps'] = empty($p['rows']) ? 10 : intval($p['rows']);
		$page['pn'] = empty($p['page']) ? 1 : intval($p['page']);
		$where = array();
		$where['is_del'] = 'N';
		if(!empty($p['qs_name']) && !empty($p['qs_value'])){
			$where[$p['qs_name'].'|l'] = $p['qs_value'];
		}		
		
		$tbl = 'shop_seo_tag|tag_id,name,title,cat_id,is_hot';
        $list = $this->getAjaxListByPage($page,$tbl,array(),$where,'tag_id desc');

        foreach ($list as $k=>$v){
        	$this->finder_list_modify_tag($list[$k]);
        }
		
        return array('total'=>$page['tcount'],'rows'=>$list); 
	}	
	
	private function finder_list_modify(&$r){
    	$r['is_view'] = $this->conf_view[$r['is_view']];
		
		$color = 'green';
		if($r['status'] == 'PENDING') $color = 'red';
    	$r['status'] = '<span style="color:'.$color.';">'.$this->conf_article_status[$r['status']].'</span>';
    	$r['add_time'] = date('Y-m-d H:i',$r['add_time']);
    	$r['cat_id'] = Custom_Model_Tools::getCatFname($this->list_cat, $r['cat_id'],' - ');
		$r['__opt'] = $this->getArticleItemOpt($r);
		
	}
	
	private function recycle_finder_list_modify(&$r){
    	$r['is_view'] = $this->conf_view[$r['is_view']];
		
		$color = 'green';
		if($r['status'] == 'PENDING') $color = 'red';
    	$r['status'] = '<span style="color:'.$color.';">'.$this->conf_article_status[$r['status']].'</span>';
    	$r['add_time'] = date('Y-m-d H:i',$r['add_time']);
    	$r['cat_id'] = Custom_Model_Tools::getCatFname($this->list_cat, $r['cat_id'],' - ');
	}
	
	private function finder_list_modify_tag(&$r){
    	$r['cat_id'] = Custom_Model_Tools::getCatFname($this->list_cat, $r['cat_id'],' - ');
		$r['__opt'] = $this->getTagItemOpt($r);
		$r['is_hot'] = $this->conf_y[$r['is_hot']];
	}
	
	private function getCatItemOpt($v){
		$rs = '<a href="#" class="easyui-menubutton" data-options="menu:\'#tree-finder-menu-'.$v['cat_id'].'\'">操作</a>';
		$rs .= '<div id="tree-finder-menu-'.$v['cat_id'].'" style="width:150px;">';
		$rs .= '<div onclick="dlg_open(\'/newsadmin/cat-add/id/'.$v['cat_id'].'\',\'cat-dlg\',\'编辑\',500,460,true);">编辑</div>';
		$rs .= '<div onclick="dlg_open(\'/newsadmin/cat-sub-add/id/'.$v['cat_id'].'\',\'cat-dlg\',\'添加子类\',500,460,true);">添加子类</div>';
		$rs .= '<div onclick="ajax_confirm(this,\'/newsadmin/cat-del/id/'.$v['cat_id'].'\',\'确定要删除吗？\',\'\',\'\',\'cat_del_after\');">删除</div>';
		$rs .= '</div>';
		return $rs;
	}
	
	private function getArticleItemOpt($v){
		$rs = '<a href="#" class="easyui-menubutton" data-options="menu:\'#tree-finder-menu-'.$v['article_id'].'\'">操作</a>';
		$rs .= '<div id="tree-finder-menu-'.$v['article_id'].'" style="width:150px;">';
		$rs .= '<div onclick="win_open(\'/newsadmin/add/id/'.$v['article_id'].'\',\'article-new-win\',\'编辑\',900,600,true);">编辑</div>';
		$rs .= '<div onclick="ajax_confirm(this,\'/newsadmin/del/id/'.$v['article_id'].'\',\'确定要删除吗？\',\'\',\'\',\'article_del_after\');">删除</div>';
		$rs .= '</div>';
		return $rs;
	}
	
	
	private function getTagItemOpt($v){
		$rs = '<a href="#" class="easyui-menubutton" data-options="menu:\'#finder-item-menu-'.$v['tag_id'].'\'">操作</a>';
		$rs .= '<div id="finder-item-menu-'.$v['tag_id'].'" style="width:150px;">';
		$rs .= '<div onclick="dlg_open(\'/newsadmin/tag-add/id/'.$v['tag_id'].'\',\'tag-dlg\',\'编辑\',500,450,true);">编辑</div>';
		$rs .= '<div onclick="ajax_confirm(this,\'/newsadmin/tag-del/id/'.$v['tag_id'].'\',\'确定要删除吗？\',\'\',\'\',\'tag_del_after\');">删除</div>';
		$rs .= '</div>';
		return $rs;
	}
	
 }