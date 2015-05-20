<?php
class Admin_Models_API_Decoration extends Admin_Models_API_Base
{
	private $upPath = 'upload/decorat/';

	public function __construct()
	{
	    parent::__construct();
		$this->_db = new Admin_Models_DB_Decoration();
	}

	public function getAllCate($pid){
		return $this->_db->getAllCate($pid);
	}

	public function getCount(){
		return $this->_db->getCount();
	}

	public function get($where,$fields,$orderBy,$page){
		return $this->_db->get($where,$fields,$orderBy,$page);
	}

	public function add($type,$pidcode){
		$this->_db->add($type,$pidcode);
	}

	/*保存数据操作*/
	public function save($params){
		$goodnumParams = $params['goodsnum'];
		$imgurlParams = $params['imgurl'];
		$isdisplayParams = $params['isdisplay'];
		$pidParams = $params['pid'];
		$sortParams = $params['sort'];
		$len = count($goodnumParams);
		foreach($goodnumParams as $key=>$value){
			$goodsobj = $this->_db->getGoodsByNum(trim($value));
		if(empty($goodsobj)) continue;
		$img_url = "";
		if(is_file($_FILES['imgupload']['tmp_name'][$key])) {
			$upload = new Custom_Model_UploadMore($key,'imgupload', $this ->upPath);
			$upload -> up(false);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return 'imgErr';
			}
			$img_url = $this -> upPath.$upload->uploadedfiles[0]['filepath'];
		}
			$pkid = $key;
			if(!empty($img_url)){
				$data['imgurl'] = $img_url;
			}else{
				$data['imgurl'] = $imgurlParams[$key];
			}

			$data['goodsid'] = $goodsobj['goods_id'];
			$data['goodsnum'] = $goodsobj['goods_sn'];
			$data['isdisplay'] = $isdisplayParams[$key];
			$data['pid'] = $pidParams[$key];
			$data['sort'] = $sortParams[$key];
			$this->_db->save($pkid ,$data);
		}
	}

	/*获取有效数据*/
	public function getByType($type,$pidcode){
		 $reslist = $this->_db->getByType($type,$pidcode);
		 $len = count($reslist);
		for($i=0;$i<$len;$i++){
			if(empty($reslist[$i]['imgurl'])){
				$reslist[$i]['imgurl'] = $reslist[$i]['goods_img'];
				if(!empty($reslist[$i]['market_price']) && $reslist[$i]['market_price'] != 0){
						$value = ($reslist[$i]['price'] /$reslist[$i]['market_price'])*10;
						$reslist[$i]['zhekou'] =round($value,2);
				}
			}
		}

		 return $reslist;
	}
	
	/**
	 * 更新显示情况
	 * @param unknown_type $id
	 * @param unknown_type $val
	 */
	public function updateDisplay($id,$val){
	    return $this->update('shop_decoration', array('isdisplay'=>$val),array('id'=>$id));
	    
	}
	
	/**
	 * 更新排序
	 * @param unknown_type $id
	 * @param unknown_type $val
	 */
	public function updateOrd($id,$val){
	    return $this->update('shop_decoration', array('sort'=>$val),array('id'=>$id));
	}
}
