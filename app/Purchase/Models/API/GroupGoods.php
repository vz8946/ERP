<?php

class Purchase_Models_API_GroupGoods
{
    /**
     * 
     * @var Purchase_Models_DB_GroupGoods
     */
	public $_db = null;

    /**
     * 错误信息
     */
	protected $error;

	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Purchase_Models_DB_GroupGoods();
		$this -> _auth = Purchase_Models_API_Auth :: getInstance() -> getAuth();
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getViewTag($where)
	{
		return array_shift($this -> _db -> getViewTag($where));
	}
	/**
     * 获取商品数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where=null,$fields='*',$page=1,$pageSize=20, $statusFlag = 1)
	{
		$orderBy = 'group_sort asc';
		if (is_array($where)) {
    		if ($where['sort']) {
    	        switch ($where['sort']){
    	            case 1:
    	                $orderBy .= ",group_id desc";
    	            break;
    	            case 2:
    	                $orderBy .= ",group_price desc";
    	            break;
    	            case 3:
    	                $orderBy .= ",group_price asc";
    	            break;
    	            default:$orderBy .= ",group_id desc";
    	            break;
    	        }
    	    }
    	    if($where['select']){
    	    	$wheresql = $where['select'];
    	    }
    	}
    	else {
    	    $wheresql = $where;
    	}
		return $this -> _db -> get($wheresql, $fields, $page, $pageSize, $orderBy, $statusFlag);
	}

	/**
	 * 得到config中的商品
	 *
	 * @param array $search
	 * @return $goods array
	 */
	public function fetchConfigGoods($search=null)
	{
		if($search){
			return $this -> _db -> fetchConfigGoods($search);
		}else{
			return false;
		}
	}

	/**
     * 获取记录总数
     *
     * @return   int
     */
	public function getCount()
	{
		return $this -> _db -> total;
	}

	/**
	 * 写入cookie.groupgoods
	 *
	 * @param int $group_id
	 * @param int $number
	 * @param string $sign
	 *
	 * @return bool
	 */
	public function writeToCookie($group_id,$number=1)
	{

		if($group_id<1){return false;}
		$totNum = $this -> getOneGoodsTotal($group_id);//某组合中商品总数
		//cookie.groupgoods构成：(组合id,购买数量,组合中商品总数*购买数量|)
		$tmp = $group_id.','.$number.','.($totNum*$number);

		if($_COOKIE['groupgoods']){
    		$groupgoods = $this -> makeGroupGoodsToArray();
    		if(is_array($groupgoods[$group_id])){
    			$groupgoods[$group_id][0] = $number;
    			$groupgoods[$group_id][1] = $totNum*$number;
    		}else{
    			$groupgoods[$group_id] = array('1',$totNum);
    		}
    		$tmp = $this->makeArrayToGroupGoods($groupgoods);
    	}
    	setcookie('groupgoods', $tmp, time () + 86400 * 365, '/');
    	return true;
	}
	/*
	 * 一个产品套组购买，组合成购车成格式
	 *
	 * */
	 public function createCartFormatOne($group_id,$number){
	 	$goods = $this -> _db -> fetchConfigGoods(array('group_id'=>$group_id));
	 	$cart_str = "";
		foreach ($goods as $k => $v){
			$total = 0;
			$goodsid = $v['product_id'];
			$total = $v['number']*$number;
			$cart_str .= $goodsid.",".$total."|";
		}
		setcookie('cart', $cart_str, time () + 86400 * 365, '/');
	 }

	/**
	 * 得到某组合中商品数量
	 *
	 * @param int $group_id
	 *
	 * @return Int
	 */
	public function getOneGoodsTotal($group_id)
	{
		$goods = $this -> _db -> fetchConfigGoods(array('group_id'=>$group_id));
		$total = 0;
		foreach ($goods as $k => $v){
			$total += $v['number'];
		}
		return $total;
	}

	/**
	 * 整合购物车中的组合商品
	 *
	 * @param array $datas
	 *
	 * @return array
	 */
	public function responseToCart($datas)
	{
		if($_COOKIE['groupgoods']){
			$tmp = $this -> makeGroupGoodsToArray();
			$html = '';
			$i = 1;
			foreach ($tmp as $k =>$v){
				$groupGoodsOne = $this -> getOne($k);
				//$chk = $this -> checkOnsaleStatus(array('group_id'=>$groupGoodsOne['group_id'], ));
				$chk = true;
				if(!$chk){$groupGoodsOne = false;}
				//如果下架状态
				if(!$groupGoodsOne){
					$this -> delOneFromCookie($k);//从cookie.groupgoods删除这个组合
					continue;//跳过
				}
				$groupGoodsOne['number'] = $v[0];
				$groupGoodsOne['amount'] = $groupGoodsOne['group_price'] * $groupGoodsOne['number'];
        		$action = Zend_Controller_Front::getInstance() -> getRequest() -> getActionName();
        		 
        		if($action == 'index')
        		{
					$html .= '<tr id="groupgoods_'.$i.'"  class="haveinside">
		    		<input type="hidden" name="ids[]" id="ids_groupgoods__'.$i.'" value="'.$i.'">
		            <td  class="t_pro" colspan="2">
		            <img src="/'.str_replace('.', '_60_60.', $groupGoodsOne['group_goods_img']).'" border="0" width="60" height="60">
		            '.$groupGoodsOne['group_goods_name'].'</td>
		            <td class="s_price"><span id="groupbox_'.$groupGoodsOne['group_id'].'_1">'.$groupGoodsOne['group_price'].'</span></td>
		            <td class="s_num" id="thenchange_'.$k.'" nums="'.$v[0].'">
		            	<a  class="cut" href="javascript:;" onclick="GGNumLess('.$k.')">-</a>
						<input type="text" size="2" value="'.$v[0].'" id="GG_buy_number_'.$k.'"  onblur="setGGNumber('.$k.',this.defaultValue)" onkeyup="this.value=this.value.replace(/\D/g,\'\');" onafterpaste="this.value=this.value.replace(/\D/g,\'\');">
						<a class="plus" href="javascript:;" onclick="GGNumAdd('.$k.')">+</a>
		            </td>
		            <td class="s_score"><div><span id="groupbox_'.$groupGoodsOne['group_id'].'_2">'.$groupGoodsOne['group_price']*$v[0].'</span></div></td>
		            <td class="s_total"  id="change_price_groupgoods_'.$i.'"><span id="groupbox_'.$groupGoodsOne['group_id'].'_3">'.$groupGoodsOne['group_price']*$v[0].'</span></td>
		            <td class="s_del"><a href="javascript:void(0);" onclick="delGroupGoods('.$k.')">删除</a></td>
		            </tr>';
						$groupGoodsOneConfig = $this -> fetchConfigGoods(array('group_id'=>$k));//组合套餐中的商品
						$j=1;
						$len = count($groupGoodsOneConfig);
						foreach ($groupGoodsOneConfig as $kk => $vv){
							if($len>0 && $j=1) $rowSpan = '<td rowspan="'.$len.'"><b>组合商品</b></td>';
							
							$html .= '<tr id="groupgoods_'.$i.'_'.$j.'">
							'.$rowSpan.'
							<td class="t_pro"><a href="/goods-'.$vv['goods_id'].'.html" target="_blank"><img src="/'.str_replace('.', '_60_60.', $vv['goods_img']).'" border="0" width="50" height="50"></a>
									<a href="/goods-'.$vv['goods_id'].'.html" align="center" target="_blank">'.$vv['goods_name'].'</a></td>
							<td class="td_right" align="center">&nbsp;</td>
							<td align="center" id="buy_number_groupgoods_'.$i.'_'.$j.'"><input type="hidden" id="buy_number_groupgoods_'.$i.'_'.$j.'" value="'.$vv['number']*$v[0].'" />'.$vv['number'].'&times;'.$v[0].'</td>
							<td class="td_right" align="center" colspan="3">&nbsp;</td>
							</tr>';
						}
						
        	}else{
        		$html .= '<tr id="groupgoods_'.$i.'" class="haveinside">
	    		<input type="hidden" name="ids[]" id="ids_groupgoods__'.$i.'" value="'.$i.'">
	            <td  class="t_pro" colspan="2">	    	
	            <img src="/'.str_replace('.', '_60_60.', $groupGoodsOne['group_goods_img']).'" border="0" width="60" height="60">
	            '.$groupGoodsOne['group_goods_name'].'</td>	            
	           	<td><span id="groupbox_'.$groupGoodsOne['group_id'].'_1">'.$groupGoodsOne['group_price'].'</span></td>
	            <td id="thenchange_'.$k.'" nums="'.$v[0].'">'.$v[0].'</td>
	            <td><div><span id="groupbox_'.$groupGoodsOne['group_id'].'_2">'.$groupGoodsOne['group_price']*$v[0].'</span></div></td>
	            <td  class="xiaoji">'.$groupGoodsOne['group_price']*$v[0].'</td>
	            </tr>';
        		$groupGoodsOneConfig = $this -> fetchConfigGoods(array('group_id'=>$k));//组合套餐中的商品
        		$j=1;
        		$len = count($groupGoodsOneConfig);
        		foreach ($groupGoodsOneConfig as $kk => $vv){
        			if($len>0 && $j=1) $rowSpan = '<td rowspan="'.$len.'"><b>组合商品</b></td>';
        			$html .= '<tr id="groupgoods_'.$i.'_'.$j.'">
        			'.$rowSpan.'	
				<td class="t_pro">
        					<a href="/goods-'.$vv['goods_id'].'.html" target="_blank"><img src="/'.str_replace('.', '_60_60.', $vv['goods_img']).'" border="0" width="60" height="60"></a>				
				<a href="/goods-'.$vv['goods_id'].'.html"  target="_blank">'.$vv['goods_name'].'</a></td>
				<td class="td_right" align="center">&nbsp;</td>
				<td align="center" id="buy_number_groupgoods_'.$i.'_'.$j.'"><input type="hidden" id="buy_number_groupgoods_'.$i.'_'.$j.'" value="'.$vv['number']*$v[0].'" />'.$vv['number'].'&times;'.$v[0].'</td>
				<td class="td_right" align="center" colspan="3">&nbsp;</td>
				</tr>';
        		}
        		
        		
        	}
			$pkPrice = $groupGoodsOne['group_price']*$v[0];
        	$datas['amount'] += $pkPrice;
        	$datas['goods_amount'] += $pkPrice;
        	$datas['package_price'] += $pkPrice;
        	$datas['number'] += $v[1];
        
        	//$datas['group_goods_summary'] 在IndexController.php中用到，作用是顶部购物车统计，在结账时候也用到
        	$arr = $this->fetchConfigGoods(array('group_id'=>$groupGoodsOne['group_id']));
        	//$arr = unserialize($arr['group_goods_config']);
        	$datas['group_goods_summary'][] = array('group_id'=>$groupGoodsOne['group_id'],'group_specification'=>$groupGoodsOne['group_specification'],'group_goods_name'=>$groupGoodsOne['group_goods_name'],'group_goods_img'=>$groupGoodsOne['group_goods_img'],'group_price'=>$groupGoodsOne['group_price'],'number'=>$v[0],'config'=>$arr);
         
		   }		
			
		   $datas['other']['group_goods'] = $html;
		   return $datas;
		}else{
			return $datas;
		}
	}

	/**
	 * 得到一个组合商品
	 *
	 * @param int $group_id
	 * @return array
	 */
	public function getOne($group_id) {
		$wheresql = " group_id = $group_id ";
		$g = $this -> _db -> get($wheresql);

		return array_shift($g);
	}

	/**
	 * 从cookie.groupgoods中删除一个组合商品
	 *
	 * @param int $group_id
	 */
	public function delOneFromCookie($group_id) {
		if($group_id>0){
			if($_COOKIE['groupgoods']){
    			$groupgoods = $this -> makeGroupGoodsToArray();
    			if($groupgoods[$group_id]){
    				unset($groupgoods[$group_id]);
    			}
    			$tmp = $this->makeArrayToGroupGoods($groupgoods);
    			setcookie('groupgoods', $tmp, time () + 86400 * 365, '/');
    			return '';
			}else{
				return '';
			}
		}else{
			return '参数错误';
		}
	}

	/**
	 * 转换cookie中的组合商品
	 *
	 * @return array
	 */
	public function makeGroupGoodsToArray() {
		$groupgoods = array();
		if ($_COOKIE['groupgoods']){
			$tmp = explode('|', $_COOKIE['groupgoods']);
			foreach ($tmp as $k => $v){
				$tmpp = explode(',', $v);
				$groupgoods[$tmpp[0]] = array($tmpp[1],$tmpp[2]);
			}
		}
		return $groupgoods;
	}

	/**
	 * 转换组合商品成字符串
	 *
	 * @param array $groupgoods
	 * @return array
	 */
	public function makeArrayToGroupGoods($groupgoods) {
		if(is_array($groupgoods)){
			$tmpArr=array();
			foreach ($groupgoods as $k => $v){
				$tmp = implode(',', $v);
				$tmpArr[] = $k.','.$tmp;
			}
			return implode('|', $tmpArr);
		}
        return false;
	}

	/**
	 * 检查组合商品是否过期
	 *
	 * @param int $group_id
	 * @return bool
	 */
	public function checkStatus($group_id) {
		if($group_id>0){
			return $this -> _db -> checkStatus($group_id);
		}
		return false;
	}

	/**
	 * 正常商品的详细页，关联组合商品->取得对应 的组合商品列表
	 *
	 * @param int $goods_id
	 *
	 * @return Array
	 */
	public function getRelateGroupGoods($goods_id) {
		if( $goods_id > 0 ) {
			$datas = $this -> _db -> getRelateGroupGoods($goods_id);
			if ( !$datas )   return false;

			for ( $i = 0; $i < count($datas); $i++ ) {				
				$configGoods = $this -> fetchConfigGoods(array('group_id'=>$datas[$i]['group_id'])); 
				unset($configGoods[$goods_id]); //移除当前商品
				$datas[$i]['item_len'] = count($configGoods);
			    $datas[$i]['group_goods_config'] = $configGoods; 
			}
		}

		return $datas;
	}

	/**
	 * 隐藏用户名中间部分
	 *
	 */
	public function formatName($name)
	{
		 $name = str_replace('1jiankang.', '163.', $name);
		 if(strstr($name, '@')){
		 	 $user_name = explode('@', $name);
		 	 $name = substr($user_name[0], 0, 3).str_repeat("*", strlen($user_name[0]-2)).'@'.$user_name[1];
		 }
		 else if(strlen($name) == 11 && preg_match("/^(((1[3|5|8]{1}[0-9]{1}))[0-9]{8})$/", $name, $m)){
		 	 $name = substr($name, 0, 3).'****'.substr($name, -4);
		 }
		 else {
		 	 if (strlen($name) < 3 || !strstr($name, '*')) $name = substr($name, 0, 3)."******";
		 }
		 return $name;
	}

	/**
	 * 得到组合商品评论
	 *
	 * @param $search array
	 * @param string $fields
	 * @param string $orderby
	 * @param int $page
	 * @param in $pageSize
	 *
	 * @return array
	 */
	public function getGroupGoodsMsg($search=null, $fields='*', $orderby=null, $page=null, $pageSize=5) {
		$where = ' where 1=1';
		if($search){
			$search['status'] && $where .= ' and status='.$search['status'];
			$search['type'] && $where .= ' and type='.$search['type'];
			$search['group_goods_id'] && $where .= ' and group_goods_id='.$search['group_goods_id'];
		}
		$rs = $this -> _db -> getGroupGoodsMsg($where, $fields, $orderby, $page, $pageSize);
		foreach ($rs['datas'] as $k=>$v){
			$rs['datas'][$k]['user_name'] = $this -> formatName($v['user_name']);
		}
		return $rs;
	}

	/**
	 * 检查ip
	 *
	 */
	public function checkIp(){
		$ipArray = array('127.0.0.1');
        if(in_array($_SERVER['REMOTE_ADDR'], $ipArray) || substr($_SERVER['REMOTE_ADDR'], 0, 9) != '192.168.0'){
        	return true;//内部直接发表
        }
	}

	/**
	 * 添加组合商品评论
	 *
	 * @param array $post
	 */
	public function commentAdd($post) {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($post, $filterChain);

        if(!$data['content']){
            return '请填写内容';
        }else{
        	$msg['content'] = $data['content'];
        }
        if(!$data['title']) {
        	$msg['title'] = substr($data['content'], 0, 30);
        }else{
        	$msg['title'] = $data['title'];
        }
    	/*$r = $this -> checkHistory($data);
		if($r) {
			switch ($r){
				case 'notLogin':
				    return '请先登录';
				case 'notBuy':
				    return '您未购买过此商品不能发表评论';
				case 'hasComment':
				    return '您已对此商品发表过评论了';
			}
		}*/
        if ($data['score']) {
            $msg['cnt1'] = $data['score'];
        }
		else    $msg['cnt1'] = (int)$data['cnt1'];
		$msg['cnt2'] = (int)$data['cnt2'];
		$msg['group_goods_id'] = $data['group_goods_id'];
		$msg['group_goods_name'] = $data['group_goods_name'];
        $msg['type'] = $data['type']?$data['type']:1 ;

		if ($data['user_name']) {
			$msg['user_id'] = 0;
		    $msg['user_name'] = $data['user_name'];
		}else{
			if($this -> _auth){
			    $msg['user_id'] = $this -> _auth['user_id'];
			    $msg['user_name'] = $this -> _auth['user_name'];
			    $msg['real_name'] = $this -> _auth['real_name'];
			    $msg['email'] = $this -> _auth['email'];
			}else{
				$msg['user_name'] = '匿名用户';
			}
		}
        $msg['add_time'] = time();
        $msg['ip'] = $_SERVER['REMOTE_ADDR'];

        if(!$this -> _db -> commentAdd($msg)){
            return '添加失败';
        }
	}

	/**
	 * 判断组合商品的上下架
	 *
	 * @param array $dat = array('configs', 'group_id')
	 *
	 * @return bool
	 */
	public function checkOnsaleStatus($dat = array()) {
		if(is_array($dat) && count($dat)){
			$configs = null;
			if($dat['configs']){
				$configs = $dat['configs'];
			}elseif($dat['group_id']){
				$configs = $this -> fetchConfigGoods(array('group_id'=>$dat['group_id']));
			}else{
				return false;
			}
            
			if(is_array($configs) && count($configs)){
				$onsaleFlag = true;
		        foreach ($configs as $v){
		        	if(isset($v['onsale'])){
			        	if( $v['onsale']!=0){
			        		$onsaleFlag = false; break;
			        	}
		        	}else{
		        		return false; break;
		        	}
		        }
		        
		        //是否下架
		        if($onsaleFlag){
		        	return true;
		        }else{
		        	$api_admin_groupgoods = new Admin_Models_API_GroupGoods();
		        	$api_admin_groupgoods -> gengxin(array('status'=>0), "group_id=".$dat['group_id']);
		        	return false;
		        }
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
