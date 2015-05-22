<?php
class Admin_Models_API_Drt extends Custom_Model_Dbadv
{
    private $tbl = 'shop_drt';
    
	public function __construct() {
	    parent::__construct();
	}
	
	/**
	 * 保存装修
	 * @param array $data
	 * @return Ambigous <boolean, unknown, string>
	 */
	public function save($data){
	    $rs = false;
	    if(!empty($data['id'])){
    	    $rs = $this->update($this->tbl, $data,array('id'=>$data['id']));
	    }else{
    	    $rs = $this->insert($this->tbl, $data);
	    }
	    return $rs;
	}
	
	/**
	 * 装修标识检测
	 * @param unknown_type $name
	 * @param unknown_type $id
	 * @return boolean
	 */
	public function checkNameExsits($name,$id = 0){
	    
	    if($id>0){
	        return $this->isIn($this->tbl, array('name'=>$name,'id|neq'=>$id));
	    }else{
    	    return $this->isIn($this->tbl, array('name'=>$name));
	    }
	}
	
	/**
	 * 根据ID取得数据
	 * @param unknown_type $id
	 * @return multitype:
	 */
	public function getById($id){
	    return $this->getRow($this->tbl,array('id'=>$id));
	}
	
	/**
	 * 根据name取得数据
	 * @param unknown_type $id
	 * @return multitype:
	 */
	public function getByName($name){
	    return $this->getRow($this->tbl,array('name'=>$name));
	}
	
	/**
	 * 根据DIS串取得数据列表
	 * @param unknown_type $ids
	 * @return multitype:
	 */
	public function getListByIds($ids){
	    return parent::getAll($this->tbl,array('id|in'=>$ids));
	}
	
	/**
	 * 取得所有装修
	 * @see Custom_Model_Dbadv::getAll()
	 */
	public function getAll(){
	    return parent::getAll($this->tbl);
	}
	
	/**
	 * 删除装修
	 * @param unknown_type $id
	 * @return number
	 */
	public function del($id){
	    return parent::delete($this->tbl,array('id|in'=>$id));
	}

	public function refresh($name,$cache_type = 'memcache',$sysRoot = SYSROOT,$tplRoot = SHOP_TPL_ROOT){
	    $xml = new Custom_Config_Xml();
	    
	    $config=$xml->getConfig();

	    $sc = $config -> view -> smarty -> toArray();
		
	    $sc['template_dir'] = '../app/Shop/Views/scripts';
	    $sc['plugin_dir'] = '../lib/Smarty/plugins';

	    require_once $sysRoot.'/lib/Smarty/Smarty.class.php';
	    $smt = new Smarty();
	    $smt->template_dir = $sc['template_dir'];
	    $smt->compile_dir = $sc['compile_dir'];
	    $smt->plugins_dir = $sc['plugin_dir'];
	    $smt->left_delimiter = $sc['left_delimiter'];
	    $smt->right_delimiter = $sc['right_delimiter'];
	    $smt->debugging = $sc['debugging'];
	    $smt->caching = 0;
	    
	    $r_drt = $this->getByName($name);
		
		if(empty($r_drt)) return false;
		
		$smt->assign('__sys_wtpl_name',$name);
		
	    $source = unserialize($r_drt['datasource']);
	    
	    if(!empty($source['link_data'])){
	        foreach ($source['link_data'] as $k=>$v){
	    
	            usort($source['link_data'][$k],array('Admin_Models_API_Drt','sortData'));
	    
	            //过滤不可用数据
	            $source['link_data'][$k] = array_filter($source['link_data'][$k],array($this,'filterData'));
	    
	        }
	    }
	     
	    if(!empty($source['brand_data'])){
	        $apiBrand = new Admin_Models_API_Brand();
	        foreach ($source['brand_data'] as $k=>$v){
	    
	            $arr_brand_id = array();
	            foreach ($v as $kk=>$vv){
	                $arr_brand_id[] = $vv['brand_id'];
	            }
	    
	            $list_brand = $apiBrand->getByIds(implode(',', $arr_brand_id));
	    
	            $k_list_brand = array();
	            foreach ($list_brand as $kk=>$vv){
	                $k_list_brand[$vv['brand_id']] = $vv;
	            }
	    
	            foreach ($v as $kk=>$vv){
	                $source['brand_data'][$k][$kk]['brand'] = $k_list_brand[$vv['brand_id']];
	            }
	    
	            usort($source['brand_data'][$k],array('Admin_Models_API_Drt','sortData'));
	    
	            //过滤不可用数据
	            $source['brand_data'][$k] = array_filter($source['brand_data'][$k],array($this,'filterData'));
	        }
	    }
		
		
	    if(!empty($source['goods_data'])){
	        $apiGoods = new Admin_Models_API_Goods();
			$apiShopGoods = new Shop_Models_API_Goods();
	        foreach ($source['goods_data'] as $k=>$v){
	    
	            $arr_goods_id = array();
	            foreach ($v as $kk=>$vv){
	                $arr_goods_id[] = $vv['goods_id'];
	            }
	    
	            $list_goods = $apiGoods->getByIds(implode(',', $arr_goods_id));

		        //优惠活动
		        $offers = new Shop_Models_API_Offers($apiShopGoods);
		        $list_goods = $offers -> responseToView($list_goods);
					
	            $k_list_goods = array();
	            foreach ($list_goods as $kk=>$vv){
	                $vv['disprice'] = sprintf("%.2f", ($vv['market_price'] - $vv['price']));
	                $vv['saleoff'] = ($vv['market_price'] == 0) ? 0 : sprintf("%.1f", (($vv['price']/$vv['market_price'])*10));
	                $k_list_goods[$vv['goods_id']] = $vv;
	            }

	            foreach ($v as $kk=>$vv){
	            	
	            	$goods = $k_list_goods[$vv['goods_id']];
					if(empty($goods['goods_id'])) continue;
	            	$brand_as_name = empty($k_list_goods[$vv['goods_id']]['as_name']) ? 'jiankang' : $k_list_goods[$vv['goods_id']]['as_name'];
					
					$disprice = 0.00;
					if(!empty($vv['market_price']) && !empty($vv['price'])){
						$disprice = sprintf("%.2f", ($vv['market_price'] - $vv['price']));
					}elseif(empty($vv['market_price']) && !empty($vv['price'])){
						$disprice = sprintf("%.2f", ($goods['market_price'] - $vv['price']));
					}elseif(!empty($vv['market_price']) && empty($vv['price'])){
						$disprice = sprintf("%.2f", ($vv['market_price'] - $goods['price']));
					}else{
						$disprice = sprintf("%.2f", ($goods['market_price'] - $goods['price']));
					}				
	                $source['goods_data'][$k][$kk]['disprice'] = $disprice;
	                $source['goods_data'][$k][$kk]['goods_url'] = '/b-'.$brand_as_name.'/detail'.$vv['goods_id'].'.html';
	                $source['goods_data'][$k][$kk]['goods'] = $goods;
	            }
	    
	            usort($source['goods_data'][$k],array('Admin_Models_API_Drt','sortData'));
	            
	            //过滤不可用数据
	            $source['goods_data'][$k] = array_filter($source['goods_data'][$k],array($this,'filterData'));
	    
	        }
	    
	    }

	    if(!empty($source['news_data'])){
	        $apiNews = new Admin_Models_API_News();
	        foreach ($source['news_data'] as $k=>$v){
	    
	            $arr_news_id = array();
	            foreach ($v as $kk=>$vv){
	                $arr_news_id[] = $vv['id'];
	            }
	    
	            $list_news = $apiNews->getByIds(implode(',', $arr_news_id));
	    
	            $k_list_news = array();
	            foreach ($list_news as $kk=>$vv){
	                $k_list_goods[$vv['id']] = $vv;
	            }
	    
	            foreach ($v as $kk=>$vv){
	                $source['news_data'][$k][$kk]['url'] = '';
	                $source['news_data'][$k][$kk]['memo'] = $k_list_goods[$vv['id']]['seoDescription'];
	                $source['news_data'][$k][$kk]['news'] = $k_list_goods[$vv['id']];
	            }
	    
	            usort($source['news_data'][$k],array('Admin_Models_API_Drt','sortData'));
	            
	            //过滤不可用数据
	            $source['news_data'][$k] = array_filter($source['news_data'][$k],array($this,'filterData'));
	    
	        }
	    
	    }

	    foreach ($source as $k=>$v){
	        foreach ($v as $kk=>$vv){
	            $smt->assign(''.$kk,$vv);
	        }
	    }
		
		$smt->assign("imgBaseUrl",$config->view->imgBaseUrl);
		
	    $t = $smt->fetch('wdgtpl/'.$r_drt['tpl'].'/default.tpl');	    
		
	    $t = '<div id="wgt-'.$r_drt['name'].'" class="widget">'.$t.'</div>';
	    if($cache_type == 'file'){
    	    $objFile = new Custom_Model_File();
    	    $objFile->writefile($tplRoot.'widget/'.$r_drt['name'].'.tpl', $t);
	    }else{
    	    $memchache = new Custom_Model_Memcached('drt');
    	    $memchache->set($name, $t);
	    }
	    return $t;
	    
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
