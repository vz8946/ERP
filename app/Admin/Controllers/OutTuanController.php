<?php
class Admin_OutTuanController extends Zend_Controller_Action
{
    /**
     * 
     * @var Admin_Models_API_OutTuan
     */
	private $_api = null;
	
	/**
     * 上传xls路径
     */
	private $upPath = 'upload/tuan';
	
	/**
	 * auth对象
	 */
	private $_auth = null;

	/**
	 * 初始化对象
	 *
	 * @return   void
	 */
	public function init()
	{
		$this -> _api = new Admin_Models_API_OutTuan();
		$this -> _auth = Admin_Models_API_Auth::getInstance() -> getAuth();
	}
	
	/**
	 * 得到外部团购商品
	 * 
	 * 
	 */
	public function goodsAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$datas = $this -> _api -> getOutTuanGoods($search, $page, 25);
        if(count($datas)>0 ){
            foreach($datas['datas'] as $k=>$v){
                if($v['goods_fid']=='0'){
                    $goodsfids[]=$v['goods_id'];
                }
            }
           $sub=$this -> _api -> getOutTuanGoods(" and goods_fid in (".implode(',',$goodsfids)." )", 1, 50, null, 1);
           foreach($datas['datas'] as $k=>$v){
               if(count($sub['datas'])>0){
                    foreach($sub['datas'] as $key=>$var){
                        if($var['goods_fid']==$v['goods_id']){
                            $datas['datas'][$k]['subs'][] = $var;
                        }
                    }
               }

            }
        }
		$pageNav = new Custom_Model_PageNav($datas['tot'], 25, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> datas = $datas['datas'];
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(null,1,1,null,1);
		$this -> view -> shops = $shops['datas'];
	}
	
	/**
	 * 添加外部团购商品
	 * 
	 */
	public function goodsAddAction() {
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			$checkRepeat = $this -> _api -> getOutTuanGoods(array('shop_id'=>$arr['shopid'], 'goods_name'=>$arr['goods_name']));
			if($checkRepeat['tot'] > 0){
				Custom_Model_Message::showMessage('已经存在', '/admin/out-tuan/goods', 1250);
			}else{
				$arr['goods_fid'] = 0;
				$arr['g_name'] = '';
				$this -> _api -> goodsAdd($arr);
				Custom_Model_Message::showMessage('添加成功', '/admin/out-tuan/goods', 1250);
			}
		}
		//取出网站
		$shops = $this -> _api -> getOutTuanShop(array('status'=>0,'shop_type'=>'tuan'), 1, 1, null, 1);
		$this -> view -> shops = $shops['datas'];
	}
	
	/**
	 * 修改外部团购商品
	 * 
	 */
	public function goodsEditAction() {
		$goods_id = (int)$this -> _request -> getParam('goods_id', 1);
		$detail = $this -> _api -> getOutTuanGoods(array('goods_id'=>$goods_id,'quan'=>1));
		if($goods_id < 1){exit('参数错误');}
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			$checkRepeat = $this -> _api -> getOutTuanGoods(array('shop_id'=>$arr['shopid'], 'goods_name'=>$arr['goods_name']), 1, 1, $goods_id);
			if($checkRepeat['tot'] > 0){
				Custom_Model_Message::showMessage('已经存在', '/admin/out-tuan/goods', 1250);
			}else{
				$this -> _api -> goodsEdit(array('goods_sn'=>trim($arr['goods_sn']),'goods_type'=>$arr['goods_type'],'shop_id'=>$arr['shopid'], 'goods_name' =>trim($arr['goods_name']),'goods_desc' => $arr['goods_desc'],'goods_price'=>$arr['goods_price'],'supply_price'=>$arr['supply_price'],'rate'=>$arr['rate'],'goods_number'=>$arr['goods_number']),"goods_id = ".$goods_id);
				if(strlen($arr['goods_sn']) == 9){
					$this -> _api -> setGroupGoods($arr['goods_sn'],$goods_id);
				}else{
					$this -> _api -> setGIDPID($arr['goods_sn'],$goods_id);
				}
				Custom_Model_Message::showMessage('修改成功', '/admin/out-tuan/goods', 1250);
			}
		}
		//取出网站
		$shops = $this -> _api -> getOutTuanShop(array('status'=>0,'shop_type'=>'tuan'), 1, 1, null, 1);
		$this -> view -> shops = $shops['datas'];
		//
		$this -> view -> detail = $detail['datas'][0];
	}
	
	/**
	 * ajax状态
	 * 
	 */
	public function goodsStatusAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', 0);if($id<1){exit('参数错误');}
		$st = (int)$this -> _request -> getParam('st', 0);if($st!=0 && $st!=1){$st = 1;}
		$this -> _api -> goodsEdit(array('status'=>$st),'goods_id='.$id);
		exit('ok');
	}
	
	/**
	 * ajax删除
	 * 
	 */
	public function goodsDelAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', 0);if($id<1){exit('参数错误');}
		$rs = $this -> _api -> goodsDel($id);
		if($rs == 'ok'){
			exit('ok');
		}elseif($rs == 'hasgoods'){
			exit('此团购下有商品存在，不能删除');
		}else{
			exit('错误');
		}
	}
	
	/**
	 * 添加子套餐
	 * 
	 */
	public function goodsAddSubAction() {
		$goods_id = (int)$this -> _request -> getParam('goods_id', 1);
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			$arr['goods_fid'] = $goods_id?$goods_id:0;
			$arr['goods_desc'] = '';
			$this -> _api -> goodsAdd($arr);
			Custom_Model_Message::showMessage('添加子套餐成功', '/admin/out-tuan/goods', 1250);
		}
		$detail = $this -> _api -> getOutTuanGoods(array('goods_id'=>$goods_id));
		$this -> view -> detail = $detail['datas'][0];
	}
	
	/**
	 * 导出商品
	 * 
	 */
	public function goodsExportAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$datas = $this -> _api -> getOutTuanGoods(array('goods_fid'=>0), 1, 1, null, true);
		//dump($datas['datas'],1,1);
		foreach ($datas['datas'] as $k=>$v){
			$sub = $this -> _api -> getOutTuanGoods(array('goods_fid'=>$v['goods_id']), 1, 1, null, 1);
			$datas['datas'][$k]['subs'] = $sub['datas'];
		}
		//dump($datas['datas'],1,1);
		$i = 0;
		foreach ($datas['datas'] as $k=>$v){
			if(is_array($v['subs']) && count($v['subs'])){//有子商品的统计子商品
				foreach ($v['subs'] as $kk=>$vv){
					$vv['suppleySinglePrice'] = $vv['supply_price']/$vv['goods_number'];
					$vv['saleSinglePrice'] = $vv['goods_price']/$vv['goods_number'];
					$vv['amt'] = $vv['goods_number']*$vv['amount'];
					$data[$i] = $vv;
					$i++;
				}
			}else{//直接统计
				$v['suppleySinglePrice'] = $v['supply_price']/$v['goods_number'];
				$v['saleSinglePrice'] = $v['goods_price']/$v['goods_number'];
				$v['amt'] = $v['goods_number']*$v['amount'];
				$data[$i] = $v;
				$i++;
			}
		}
		//dump($data,1,1);
		foreach ($data as $k=>$v){
			$shops[$v['shop_name']][] = $v;
		}
		//dump($shops,1,1);
		$this -> _api -> goodsExport($shops);
	}
	
	/**
	 * 外部团购订单管理
	 * 
	 */
	public function orderAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		if(count($search)<5){
			$search['fromdate'] = date("Y-m-d H:i:s", time()-86400*10);
			$search['todate'] =  date("Y-m-d H:i:s", time());
		}
		$datas = $this -> _api -> getOutTuanOrder($search, $page, 25);
		$this -> view -> datas = $datas['datas'];

		$this -> view -> totalPrice = $datas['totalPrice'];
		$this -> view -> totalSupplyPrice = $datas['totalSupplyPrice'];
		$this -> view -> totalFee = $datas['totalFee'];
        $this -> view -> totalAmount = $datas['totalAmount'];

		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($datas['tot'], 25, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $search;
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(null,1,1,null,1);
		$this -> view -> shops = $shops['datas'];
		//订单导入人
		$add_user = $this -> _api -> getAddUser();
		$this -> view -> add_user = $add_user;
		//物流公司
		$logistic = $this -> _api -> getLogistic();
		$this -> view -> logisticList = $logistic;
	}
	
	/**
	 * 添加订单
	 *
	 * 
	 */
	public function orderAddAction() {
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			$arr['add_user'] = $this -> _auth['admin_id'];
			$rs = $this -> _api -> orderAdd($arr);
			if($rs == 'ok'){$msg = '添加成功';}
			elseif($rs == 'uploaderr'){$msg = '上传文件出错';}
			elseif($rs == 'rp'){$msg = '订单重复，请核实';}
			elseif($rs == 'errSubGoodsName'){$msg = '子商品不匹配';}
			else{$msg = '出现错误，请稍后重试';}
			Custom_Model_Message::showMessage($msg, '/admin/out-tuan/order', 1250);
		}
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(array('status'=>0,'shop_type'=>'tuan'),1,1,null,1);
		$this -> view -> shops = $shops['datas'];
	}
	
	/**
	 * 修改订单
	 * 
	 */
	public function orderEditAction() {
		$id = (int)$this -> _request -> getParam('id', 0);
		if($id<1){$this -> _redirect('/admin/out-tuan/order');}
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			$set=array('name'=>$arr['name'],'phone'=>$arr['phone'],'mobile'=>$arr['mobile'],'postcode'=>$arr['postcode'],'addr'=>$arr['addr'],'remark'=>$arr['remark'],'amount'=>$arr['amount'],'logistics'=>$arr['logistics'],'print'=>$arr['print'],'logistics_com'=>$arr['logistics_com'],'logistics_no'=>$arr['logistics_no'],'add_user'=>$this -> _auth['admin_id'],'check_stock'=>$arr['check_stock']);
			$this -> _api -> orderEdit($set,"id = ".$id);
			Custom_Model_Message::showMessage('修改成功', '/admin/out-tuan/order', 1250);
		}
		//
		$detail = $this -> _api -> getOutTuanOrder(array('id'=>$id),1,1);
		$detail = $detail['datas'][0];
		if(is_array($detail) && count($detail)){
			//得到“订单导入人”
			$addUser = $this -> _api -> getAdminUser($detail['add_user']);
			//得到“订单打印人”
			$printUser = $this -> _api -> getAdminUser($detail['print_user']);
			//
			$detail['addUser'] = $addUser;
			$detail['printUser'] = $printUser;
			$this -> view -> detail = $detail;
			$comments = explode('|||', $detail['comments']);
			$this -> view -> comments = $comments;
			//物流公司
			$logistic = $this -> _api -> getLogistic();
			$this -> view -> logisticList = $logistic;
		}else{
			$this -> _redirect('/admin/out-tuan/order');
		}
	}
	
	/**
	 * 真正 删除单条订单
	 * 
	 */
	public function orderDelOneAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$order_id = $this -> _request -> getParam('order_sn', 0);if($order_id<1){exit('请输入订单号');}
		$this -> _api -> orderDelOne($order_id);
		exit('ok');
	}
	
	/**
	 * ajax添加客服备注
	 * 
	 */
	public function commentAddAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$content = $this -> _request -> getParam('content', '');if($content==''){exit('content');}
		$id = (int)$this -> _request -> getParam('id', 0);if($id<1){exit('id');}
		$this -> _api -> commentAdd($id, $this -> _auth['real_name'], $content);
		exit('ok');
	}
	
	/**
	 * ajax上传xls
	 * 
	 */
	public function xlsUploadAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
		$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
		$shop_id = (int)$arr['shop_id'];if($shop_id<1){exit("参数错误");}
		$goods_id = (int)$arr['goods_id'];if($goods_id<1){exit("参数错误");}
		if(is_file($_FILES['xls']['tmp_name'])) {
			//上传
			$upload = new Custom_Model_Upload('xls', $this -> upPath);
			$upload -> up();
			if($upload -> error()){
				$this -> error = $upload -> error();
				return 'uploaderr';
			}
			$xls_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			//保存到表shop_out_tuan_xls
			$id = $this -> _api -> xlsAdd($shop_id,$goods_id,$xls_url);
			exit($id);
		}
		exit('error');
	}
	
	/**
	 * ajax得到xls文件字段,并转化成<select>
	 * 
	 */
	public function getSelectAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', '');
		if($id < 1){ exit('err');}
		$rs = $this -> _api -> readXls($id, 1);
		//转换成select
		if(is_array($rs) && count($rs)){
			//$select = '<option value="n">请选择</option>';
			/*foreach ($rs as $k=>$v){
				$select[$k]['k']= $k;
				$select[$k]['v']= $v;
			}*/
			echo json_encode($rs);
			exit;
		}else{
			exit('xlsnone');
		}
	}
	
	/**
	 * 打印外部团购订单列表
	 * 
	 */
	public function printlistAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$remark = (int)$this -> _request -> getParam('remark', 0);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		if($remark==1){
			$datas = $this -> _api -> getOutTuanOrder(array('remark'=>'yes','logistics'=>'off','check_stock'=>1), $page, 25);
			$this -> view -> remark = 1;
		}else{
			$datas = $this -> _api -> getOutTuanOrder(array('logistics'=>'off','check_stock'=>1), $page, 25);
		}
		$this -> view -> datas = $datas['datas'];
		$pageNav = new Custom_Model_PageNav($datas['tot'], 25, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(null,1,1,null,1);
		$this -> view -> shops = $shops['datas'];
		//物流公司
		$logistic = $this -> _api -> getLogistic();
		$this -> view -> logisticList = $logistic;
	}
	
	/**
	 * 打印订单
	 * 
	 * 可以按批次打印$pact=='pbatch'
	 * 可以按选中项打印$pact=='pselect'
	 */
	public function printsAction() {
		$pact = $this -> _request -> getParam('pact', '');
		$batch = $this -> _request -> getParam('batch', '');
		$logistic = $this -> _request -> getParam('logistic', '');
		if($pact==''){
			Custom_Model_Message::showMessage('参数pact错误', '/admin/out-tuan/printlist', 1250);
		}
		//条件
		if($pact=='pbatch'){
			$rs = $this -> _api -> getOutTuanOrder(array('batch'=>$batch,'print'=>'off','check_stock'=>1),1,1,null,1);
			if(is_array($rs) && count($rs)){
				;
			}else{
				Custom_Model_Message::showMessage('订单不存在', '/admin/out-tuan/printlist', 1250);
			}
		}elseif($pact=='pselect'){
			$xz = $this -> _request -> getParam('xz', '');
			if(is_array($xz) && count($xz)){
				$str = implode(',', $xz);
				$rs = $this -> _api -> getOutTuanOrder(array('id'=>$str,'print'=>'off'),1,1,null,1);
			}else{
				Custom_Model_Message::showMessage('请选择需要打印的订单', '/admin/out-tuan/printlist', 1250);
			}
		}else{
			Custom_Model_Message::showMessage('参数pact错误', '/admin/out-tuan/printlist', 1250);
		}
		$rs = $rs['datas'];
		if(count($rs) && $logistic!=''){
			//整理数据，和"快递单模板"相匹配
			foreach ($rs as $k=>$v){
				$rs[$k]['consignee'] = $v['name'];
				$rs[$k]['tel'] = $v['phone'];
				$rs[$k]['address'] = $v['addr'];
				$rs[$k]['bill_no'] = $v['order_sn'];
				$rs[$k]['print_remark'] = $v['batch'].'<br>'.$v['remark'];
				$rs[$k]['goods_number'] = $v['amount'];
			}
			$logisticAPI = new Admin_Models_API_Logistic();
			$template = $logisticAPI -> getLogisticTemplate($logistic, 2);
			if ( !$template ) {
                die('找不到对应模板!');
            }
			foreach ($rs as $num => $data){
		        $templates[] = $logisticAPI -> parseTemplate($template, $data);
	        }
	        $this -> view -> templates = $templates;
            echo $this -> view -> render('transport/print2.tpl');
            exit;
		}else{
			exit('没有内容');
		}
	}
	
	/**
	 * 更改批次的打印状态
	 * 
	 * 只更新打印状态
	 */
	public function batchChangePrintAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');if($batch==''){exit('请输入批次号');}
		$this -> _api -> orderEdit(array('print'=>1,'print_user'=>$this -> _auth['admin_id']),'batch='.$batch.' and print<1 and check_stock=1');
		exit('ok');
	}
	
	/**
	 * 批次填写快递单号
	 * 
	 * 更新“物流状态、物流公司、快递单号”
	 */
	public function batchFillNoAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		if($batch==''){
			$this -> _goto('请输入批次号', '/admin/out-tuan/printlist');
		}
		$logistic = $this -> _request -> getParam('logistic', '');
		if($logistic==''){
			$this -> _goto('请选择物流公司', '/admin/out-tuan/printlist');
		}
		$beginhao = (double)$this -> _request -> getParam('beginhao', 0);
		if($beginhao==0){
			$this -> _goto('请输入首个单号', '/admin/out-tuan/printlist');
		}
		$zj = (int)$this -> _request -> getParam('zj', 1);
		
		//$batch
		$batch_detail = $this -> _api -> getBatch(array('batch'=>$batch));
		if($batch_detail['tot'] < 1){$this -> _goto('批次号有误', '/admin/out-tuan/printlist');}
		
		$rs = $this -> _api -> getOutTuanOrder(array('batch'=>$batch,'print'=>0,'check_stock'=>1),1,1,null,1);
		$rs = $rs['datas'];
		
		if(is_array($rs) && count($rs)){
			//快递公司
			$logs = $this -> _api -> getLogistic();
			foreach ($logs as $index=>$val){
				$tmep[$val['logistic_code']] = $val['name'];
			}
			$logs = $tmep; unset($tmep);
			//
			$sms= new  Custom_Model_Sms();
			//
			foreach ($rs as $v){
				//检查是否存在这个状态，手工填的单号、刷单、没有库存   跳过
				$check = $this -> _api -> getOutTuanOrder(array('id'=>$v['id'],'logistics'=>'off','check_stock'=>1),1,1);
				//更新
				$this -> _api -> orderEdit(array('logistics'=>1,'logistics_com'=>$logistic,'logistics_no'=>$beginhao,'logistics_time'=>time()),'id='.$v['id'].' and logistics=0');
				//
				if($check['tot']>0){
					/* Begin::发送短信*/
			
					$detail = $this -> _api -> getOutTuanOrder(array('id'=>$v['id']),1,1);
					$detail = $detail['datas'][0];
					if(mb_strlen($detail['order_sn'])>12){$detail['order_sn'] = '';}
					$msg = $detail['name'].'您在'.$detail['shop_name'].'的订单'.$detail['order_sn'].'已发出，快递为'.$logs[$detail['logistics_com']].'运单号为'.$detail['logistics_no'].'请注意查收';
					$response = $sms->send($detail['mobile'],$msg);
			
					/* End::发送短信*/
					if($zj == 1) $beginhao+=1;
					else $beginhao-=1;
				}
			}
		}
		//检查是否全部打印了
		$logistics = 0;
		$batch_order_stock_1 = $this -> _api -> getOutTuanOrderCount(array('batch'=>$batch, 'check_stock'=>1));
		$batch_order_stock_all = $this -> _api -> getOutTuanOrderCount(array('batch'=>$batch));
		if($batch_order_stock_1 == $batch_order_stock_all){
			$logistics = $cut_stock = 2;
		}else{
			$logistics = $cut_stock = 1;
		}
		//更新shop_out_tuan_batch表
		$set = array('logistics'=>$logistics, 'cut_stock'=>$cut_stock);
		$this -> _api -> batchUpdate($set, " batch=".$batch);
		$this -> _goto('批次填写快递单号成功', '/admin/out-tuan/printlist');
	}
	
	/**
	 * 打印批次销售汇总单
	 * 
	 */
	public function batchPrintSummaryAction() {
		//$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		//
		$summary = $this -> _api -> batchPrintSummary($batch);
		$this -> view -> summarys = $summary;
	}
	
	/**
	 * ajax更新order.logistics_no
	 * 
	 * printlist页面的选择项填写快递单号
	 */
	public function ajaxUpdateOrderAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', 1);if($id<1){exit('参数错误');}
		$logistic = $this -> _request -> getParam('logistic', '');if($logistic==''){exit('请选择物流公司');}
		$ln = $this -> _request -> getParam('ln', '');if($ln==''){exit('请填写快递单号');}
		$this -> _api -> orderEdit(array('logistics_no' => $ln),"id = ".$id);
		/* Begin::发送短信*/
		$detail = $this -> _api -> getOutTuanOrder(array('id'=>$id),1,1);
		$detail = $detail['datas'][0];
		//
		$logistics = $this -> _api -> getLogistic();
		foreach ($logistics as $index=>$val){
			$tmep[$val['logistic_code']] = $val['name'];
		}
		$logistics = $tmep; unset($tmep);
		//

		$sms= new  Custom_Model_Sms();
		if(mb_strlen($detail['order_sn'])>12){$detail['order_sn'] = '';}
		$msg = $detail['name'].'您在'.$detail['shop_name'].'的订单'.$detail['order_sn'].'已发出，快递为'.$logistics[$detail['logistics_com']].'运单号为'.$detail['logistics_no'].'请注意查收';
		$response = $sms->send($detail['mobile'],$msg);

		/* End::发送短信*/
		exit('ok');
	}
	
	/**
	 * 更新选中项的打印状态
	 * 
	 */
	public function selectChangePrintAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$ids = $this -> _request -> getParam('ids', '');
		$ids = substr($ids, 0, -1);
		if($ids==''){exit('请选择要更新打印状态的订单');}
		$this -> _api -> orderEdit(array('print'=>1,'print_user'=>$this -> _auth['admin_id']),"id in(".$ids.")");
		exit('ok');
	}
	
	/**
	 * 更改选中项的物流状态
	 * 
	 */
	public function selectLogisticsAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$ids = $this -> _request -> getParam('ids', '');
		$ids = substr($ids, 0, -1);
		if($ids==''){
			$this -> _goto('请选择订单', '/admin/out-tuan/printlist');
		}
		$logistic = $this -> _request -> getParam('logistics', '');
		if($logistic==''){
			$this -> _goto('请选择物流公司', '/admin/out-tuan/printlist');
		}
		$this -> _api -> orderEdit(array('logistics'=>1,'logistics_com'=>$logistic),"id in(".$ids.")");
		$this -> _goto('更改选中项的物流状态成功', '/admin/out-tuan/printlist');
	}
	
	/**
	 * 导出批次excel
	 * 
	 */
	public function exportBatchOrderAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		//先检查是否有此批次
		$check = $this -> _api -> getOutTuanOrder(array('batch'=>$batch, 'ischeat'=>'off'),1,1);
		if($check['tot']>0){
			$this -> _api -> exportBatchOrder($batch);
		}else{
			$this -> _goto('不存在此批次号');
		}
	}
	
	/**
	 * ajax删除订单
	 * 
	 */
	public function selectDeleteAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$ids = $this -> _request -> getParam('ids', '');
		$ids = substr($ids, 0, -1);
		if($ids==''){exit('请选择订单');}
		$st = $this -> _request -> getParam('st', 'off');
		if($st=='on'){$st=1;}else{$st=0;}
		//只改变status，并不是真正的删除
		$this -> _api -> orderEdit(array('status'=>$st),"id in(".$ids.")");
		exit('ok');
	}
	
	/**
	 * 跳转
	 */
	public function _goto($msg,$url='/admin/out-tuan/order') {
		if(trim($msg)==''){header("Location:$url");}
		else{echo '<script type="text/javascript">alert("'.trim($msg).'");window.location="'.$url.'";window.event.returnValue=false;</script>';}
		exit;
	}
	
	/**
	 * 重置恢复批次订单
	 * logistics,print,logistics_no,print_user
	 */
	public function resetBatchOrderAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$this -> _api -> orderEdit(array('logistics'=>0,'print'=>0,'logistics_no'=>'','logistics_com'=>'','print_user'=>''),"batch=".$batch." and ischeat=0 ");
		$this -> _goto('重置批次订单成功');
	}
	
	/**
	 * 真正删除批次
	 * 
	 */
	public function deleteBatchOrderAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$this -> _api -> deleteBatchOrder($batch);
		$this -> _goto('删除批次成功');
	}
	
	/**
	 * 更改批次结款状态
	 * 
	 */
	public function clearBatchAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');if($batch==''){exit('请输入批次号');}
		$this -> _api -> orderEdit(array('isclear'=>1),'batch='.$batch);
		$this -> _goto('更改批次结款状态成功');
	}
	
	/**
	 * 期数管理
	 * 
	 */
	public function termAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$datas = $this -> _api -> getTerm($search, $page, 25, ' id desc ');
		$this -> view -> datas = $datas['datas'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($datas['tot'], 25, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(null,1,1,null,1);
		$this -> view -> shops = $shops['datas'];
	}
	
	/**
	 * 期数添加
	 * 
	 */
	public function termAddAction() {
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			$arr['fromdate'] = strtotime($arr['fromdate']);
			$arr['todate'] = strtotime($arr['todate']);
			//检查是否重复
			$checkRepeat = $this -> _api -> getTerm(array('shop_id'=>$arr['shopid'], 'goods_id'=>$arr['goods_id'], 'term'=>$arr['term']));
			if($checkRepeat['tot'] > 0){
				Custom_Model_Message::showMessage('期数已经存在', '/admin/out-tuan/term', 1250);
			}else{
				$this -> _api -> termAdd($arr);
				Custom_Model_Message::showMessage('添加成功', '/admin/out-tuan/term', 1250);
			}
		}
		//取出网站
		$shops = $this -> _api -> getOutTuanShop(array('status'=>0,'shop_type'=>'tuan'), 1, 1, null, 1);
		$this -> view -> shops = $shops['datas'];
	}
	
	/**
	 * ajax得到网站的团购商品
	 * 
	 */
	public function getShopGoodsAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$shopid = (int)$this -> _request -> getParam('shopid', 0);
		if($shopid < 1){exit('noshopid');}
		$goods = $this -> _api -> getShopGoods($shopid);
		if($goods['tot']<1){ exit('noshopgoods'); }
		$str = '<select name="goods_id" id="goods_id" onchange="getSubTerm()"><option value="0">请选择</option>';
		foreach ($goods['datas'] as $k=>$v){
			$str .= '<option value="'.$v['goods_id'].'">'.$v['goods_name'].'</option>';
		}
		$str .= '<select>';
		exit($str);
	}
	
	/**
	 * ajax删除
	 * 
	 */
	public function termDeleteAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', 0);
		if($id<1){exit('参数错误');}
		$rs =$this -> _api -> termDelete($id);
		exit($rs);
	}
	
	/**
	 * 更新期数
	 * 
	 * 
	 */
	public function termEditAction() {
		$id = (int)$this -> _request -> getParam('id', 0);
		if($id < 1){exit('参数错误');}
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			$arr['fromdate'] = strtotime($arr['fromdate']);
			$arr['todate'] = strtotime($arr['todate']);
			//检查是否重复
			$checkRepeat = $this -> _api -> getTerm(array('term'=>$arr['term'], 'stime'=>$arr['fromdate'], 'etime'=>$arr['todate']),1,1,null,$id);
			if($checkRepeat['tot'] > 0){
				Custom_Model_Message::showMessage('已经存在', '/admin/out-tuan/term', 1250);
			}else{
				$row = array('term'=>$arr['term'],'stime'=>$arr['fromdate'],'etime'=>$arr['todate'],'remark'=>$arr['remark'],'amount'=>$arr['amount']);
				$this -> _api -> termEdit($row,"id=".$id);
				Custom_Model_Message::showMessage('修改成功', '/admin/out-tuan/term', 1250);
			}
		}
		//取出网站
		$shops = $this -> _api -> getOutTuanShop(array('status'=>0,'shop_type'=>'tuan'), 1, 1, null, 1);
		$this -> view -> shops = $shops['datas'];
		//
		$detail = $this -> _api -> getTerm(array('id'=>$id));
		$this -> view -> detail = $detail['datas'][0];
		if($detail['tot']<1){exit('没有找到相应记录');}
		//取出此网站下的商品
		$goods = $this -> _api -> getOutTuanGoods(array('goods_id'=>$v['goods_id'],'status'=>'on'), 1, 1, null, 1);
		$this -> view -> goods = $goods['datas'];
	}
	
	/**
	 * ajax更新状态
	 * 
	 */
	public function termStatusAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', 0);
		if($id<1){exit('参数错误');}
		$st = (int)$this -> _request -> getParam('st', 0);
		if($st!=0 && $st!=1){$st=1;};
		$row = array('status'=>$st);
		$this -> _api -> termEdit($row,"id=".$id);
		exit('ok');
	}
	
	/**
	 * ajax 得到商品对应期数<select>
	 * 
	 */
	public function getTermSelectAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$goods_id = (int)$this -> _request -> getParam('goods_id', 0);
		if($goods_id<1){exit('参数错误');}
		$terms = $datas = $this -> _api -> getTerm(array('goods_id'=>$goods_id,'status'=>'on'), 1, 1, ' id desc ', null, 1);
		$str = '<select name="term_id" id="term_id" onchange="setTerm()"><option value="0">请选择</option>';
		foreach ($terms['datas'] as $v){
			$str .= '<option value="'.$v['id'].'">'.$v['term'].'</option>';
		}
		$str .= '</select>';
		exit($str);
	}
	
	/**
	 * ajax 得到网站下的商品期数 <input type="checkbox">
	 * 
	 */
	public function getShopTermCheckboxAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$shop_id = (int)$this -> _request -> getParam('shop_id', 0);
		if($shop_id<1){ exit('noshopid'); }
		$terms = $datas = $this -> _api -> getTerm(array('shop_id'=>$shop_id,'clearstatus'=>'zero','status'=>'on'), 1, 1, ' id desc ', null, 1);
		if($terms['tot']<1){exit('noterm');}
		foreach ($terms['datas'] as $v){
			if($v['clearstatus']==0){
				$clearstatus = '未结算';
			}elseif($v['clearstatus']==1){
				$clearstatus = '结算中';
			}elseif($v['clearsattus']==2){
				$clearstatus = '结清';
			}else{
				$clearstatus = '未知';
			}
			$amount += $v['amount'];
			//$str .= '<input type="checkbox" name="terms[]" value="'.$v['id'].'" onclick="calculateAmount()" />&nbsp;'.$v['shop_name'].'-<font color="#09F">'.$v['goods_name'].'</font>-<font color="red">'.$v['term'].'</font>-<font color="#999999">('.date('Y-m-d',$v['stime']).'~'.date('Y-m-d',$v['etime']).')('.$clearstatus.')(￥ '.$v['amount'].' )</font><br />';
			$str .= '<input type="checkbox" name="terms[]" value="'.$v['id'].'" onclick="calculateAmount()" />&nbsp;'.$v['shop_name'].'-<font color="#09F">'.$v['goods_name'].'</font>-<font color="red">'.$v['term'].'</font>-<font color="#999999">('.date('Y-m-d',$v['stime']).'~'.date('Y-m-d',$v['etime']).')('.$clearstatus.')</font><br />';
		}
		$str .= '<span id="termsamount"></span>';
		exit($str);
	}
	
	/**
	 * ajax 得到选中期数的总额
	 * 
	 */
	public function getTermsAmountAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$terms = $this -> _request -> getParam('terms', '');
		$terms = trim($terms);
		if($terms == ''){exit('noterms');}
		$amount = $this -> _api -> getTermsAmount($terms);
		exit($amount);
		exit($terms);
	}
	
	/**
	 * 结款单申请
	 * 
	 */
	public function billAskAction() {
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			//
			$arr['add_name'] = $this -> _auth['real_name'];
			$arr['remark'] = $this -> _auth['real_name'].'@'.$arr['remark'].'|||';
			//
			if(is_array($arr['terms']) && count($arr['terms'])){
				foreach ($arr['terms'] as $v){
					$tmp[] = $v;
				}
			}else{
				Custom_Model_Message::showMessage('请选择期数', '/admin/out-tuan/bill-ask', 1250);
			}
			$arr['termids'] = implode(',', $tmp);
			//插入
			$rs = $this -> _api -> billAskAdd($arr);
			//更新shop_out_tuan_term
			$row = array('clearstatus'=>1);
			$this -> _api -> termEdit($row,"id in (".$arr['termids'].")");
			//
			Custom_Model_Message::showMessage('开单成功', '/admin/out-tuan/bill-ask', 1250);
		}
		//取出网站
		$shops = $this -> _api -> getOutTuanShop(array('status'=>0,'shop_type'=>'tuan'), 1, 1, null, 1);
		$this -> view -> shops = $shops['datas'];
	}
	
	/**
	 * 结款单审核列表
	 * 
	 */
	public function billAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$datas = $this -> _api -> getBill($search, $page, 25, ' id desc ');
		$this -> view -> datas = $datas['datas'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($datas['tot'], 25, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(null,1,1,null,1);
		$this -> view -> shops = $shops['datas'];
	}
	
	/**
	 * 结款单审核
	 * 
	 */
	public function billVerifyAction() {
		$id = (int)$this -> _request -> getParam('id', 1);
		if($id < 1){exit('参数错误');}
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			if($arr['clear_amount']=='' || $arr['remarks']==''){
				Custom_Model_Message::showMessage('请填写结款总额、备注', '/admin/out-tuan/goods', 1250);
			}
			if($arr['ori_clear_amount'] != $arr['clear_amount']){
				if($arr['ori_clear_amount'] > $arr['clear_amount']){
					$str = '单据原总金额为：'.$arr['ori_clear_amount'].'，现总金额调整为：'.$arr['clear_amount'].'，减少：'.(number_format(($arr['ori_clear_amount'] - $arr['clear_amount']),2));
				}elseif($arr['ori_clear_amount'] < $arr['clear_amount']){
					$str = '单据原总金额为：'.$arr['ori_clear_amount'].'，现总金额调整为：'.$arr['clear_amount'].'，增加：'.(number_format(($arr['clear_amount'] - $arr['ori_clear_amount']),2));
				}else{$str='';}
				$arr['remarks'] = $this -> _auth['real_name'].'@'.$arr['remarks'].'@'.$str.'|||';
			}else{
				$arr['remarks'] = $this -> _auth['real_name'].'@'.$arr['remarks'].'|||';
			}
			$arr['check_time'] = time();
			$arr['check_name'] = $this -> _auth['real_name'];
			$this -> _api -> billVerify($arr);
			//判断是否“无效单”
			if($arr['check_status']==2){
				$detail = $this -> _api -> getBill(array('id'=>$id));
				$this -> _api -> termEdit(array('clearstatus'=>0),"id in (".$detail['datas'][0]['term_ids'].")");
			}
			//解锁
			$this -> _api -> resetlock(array('locker'=>''),' id ='.$id);
			//
			Custom_Model_Message::showMessage('操作成功', '/admin/out-tuan/bill', 1250);
			
		}
		//
		$detail = $this -> _api -> getBill(array('id'=>$id));
		$termids = explode(',', $detail['datas'][0]['term_ids']);
		$datas['datas'][0]['termids'] = $termids;
		foreach ($termids as $v){
			$ds = $this -> _api -> getTerm(array('id'=>$v), $page, 25, ' id desc ', null, 1);
			if($ds['tot']>0){
				$detail['datas'][0]['terms'][] = $ds['datas'][0];
			}
			$amt += $ds['datas'][0]['amount'];
		}
		$this -> view -> amt = $amt;
		//
		$detail['datas'][0]['remarks'] = explode('|||', $detail['datas'][0]['remarks']);
		//
		$this -> view -> detail = $detail['datas'][0];
		//取出收款纪录
		$logs = $this -> _api -> getFinanceLog($id);
		$this -> view -> logs = $logs;
		//是否锁定
		$islock = ($detail['datas'][0]['locker']==$this -> _auth['real_name'])?1:2;
		$this -> view -> islock = $islock;
	}
	
	/**
	 * 结算列表
	 * 
	 */
	public function billClearAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$search['check_status'] = 'one';
		$datas = $this -> _api -> getBill($search, $page, 25, ' id desc ');
		$this -> view -> datas = $datas['datas'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($datas['tot'], 25, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(null,1,1,null,1);
		$this -> view -> shops = $shops['datas'];
	}
	
	/**
	 * 结算do
	 * 
	 */
	public function billClearDoAction() {
		$id = (int)$this -> _request -> getParam('id', 1);
		if($id < 1){exit('参数错误');}
		if($this -> _request -> isPost()){
			$arr = Custom_Model_DeepTreat::filterArray($this -> _request -> getPost(), 'strip_tags');
			$arr = Custom_Model_DeepTreat::filterArray($arr, 'htmlspecialchars');
			if($arr['receive']=='' || $arr['remarks']==''){
				Custom_Model_Message::showMessage('请填写收款总额、备注', '/admin/out-tuan/goods', 1250);
			}
			$arr['clearstatus'] = (($arr['clear_amount']-$arr['adjust_amount']-$arr['receive']-$arr['real_back_amount'])>0)?1:2;
			$this -> _api -> billClearDo($arr);
			//插入记录表
			$arr['add_name'] = $this -> _auth['real_name'];
			$this -> _api -> addFinanceLog($arr);
			//修改状态(shop_out_tuan_term)
			$detail = $this -> _api -> getBill(array('id'=>$id));
			$this -> _api -> termEdit(array('clearstatus'=>$arr['clearstatus']),"id in (".$detail['datas'][0]['term_ids'].")");
			//解锁
			$this -> _api -> resetlock(array('locker'=>''),' id ='.$id);
			//期数的订单变更状态
			//if($arr['clearstatus']==2){
			$rs = $this -> _api -> getBill(array('id'=>$id));
			$this -> _api -> termOrderClear(array('isclear'=>$arr['clearstatus'],'clear_time'=>time())," term_id in (".$rs['datas'][0]['term_ids'].")");
			//}
			//
			Custom_Model_Message::showMessage('操作成功', '/admin/out-tuan/bill-clear', 1250);
			
		}
		//
		$detail = $this -> _api -> getBill(array('id'=>$id));
		$termids = explode(',', $detail['datas'][0]['term_ids']);
		$datas['datas'][0]['termids'] = $termids;
		foreach ($termids as $v){
			$ds = $this -> _api -> getTerm(array('id'=>$v), $page, 25, ' id desc ', null, 1);
			if($ds['tot']>0){
				$detail['datas'][0]['terms'][] = $ds['datas'][0];
			}
			$amt += $ds['datas'][0]['amount'];
		}
		$this -> view -> amt = $amt;
		//
		$detail['datas'][0]['remarks'] = explode('|||', $detail['datas'][0]['remarks']);
		//
		$detail['datas'][0]['remain'] = $detail['datas'][0]['clear_amount'] - $detail['datas'][0]['real_back_amount'];
		$this -> view -> detail = $detail['datas'][0];
		//取出收款纪录
		$logs = $this -> _api -> getFinanceLog($id);
		$this -> view -> logs = $logs;
		//是否锁定
		$islock = ($detail['datas'][0]['locker']==$this -> _auth['real_name'])?1:2;
		$this -> view -> islock = $islock;
	}
	
	/**
	 * 锁定
	 * 
	 */
	public function lockFinanceAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$lockids = $this -> _request -> getParam('lockids', '');
		$fromwhere = $this -> _request -> getParam('fromwhere', '');
		if($fromwhere == 'caiwu'){$fromwhere = '/admin/out-tuan/bill-clear';}
		else{$fromwhere = '/admin/out-tuan/bill';}
		if(is_array($lockids) && count($lockids)){
			$rs = $this -> _api -> lockFinance(array('locker'=>$this -> _auth['real_name']),$lockids);
			if($rs=='noselect'){
				$this -> _goto('请选择被未锁定的纪录',$fromwhere);
			}
			$this -> _goto('',$fromwhere);
		}else{
			$this -> _goto('请选择',$fromwhere);
		}
	}
	
	/**
	 * 解锁
	 * 
	 */
	public function unlockFinanceAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$lockids = $this -> _request -> getParam('lockids', '');
		$fromwhere = $this -> _request -> getParam('fromwhere', '');
		if($fromwhere == 'caiwu'){$fromwhere = '/admin/out-tuan/bill-clear';}
		else{$fromwhere = '/admin/out-tuan/bill';}
		if(is_array($lockids) && count($lockids)){
			$rs = $this -> _api -> unlockFinance(array('locker'=>''),$lockids,$this -> _auth['real_name']);
			if($rs=='noselect'){
				$this -> _goto('请选择被自己锁定的纪录',$fromwhere);
			}
			$this -> _goto('',$fromwhere);
		}else{
			$this -> _goto('请选择',$fromwhere);
		}
	}
	
	/**
	 * ajax 得到某商品下的期数
	 * 
	 */
	public function getGoodsSubTermAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$goods_id = (int)$this -> _request -> getParam('goods_id', 0);
		if($goods_id<1){exit('nogoodsid');}
		$rs = $this -> _api -> getTerm(array('goods_id'=>$goods_id),1,1,null,null,1);
		if($rs['tot']<1){exit('');}
		$str = '<span style="color:#999;">已经存在：';
		foreach ($rs['datas'] as $v){
			$str .= $v['term'].'&nbsp;&nbsp;&nbsp;';
		}
		$str .= '</span>';
		exit($str);
	}
	
	/**
	 * ajax 快递单不连续处理
	 * 假如这批次有100单，快递单只有50张，打完再续加快递单的时候，已经不连号了
	 * 
	 */
	public function breakNoAction() {
		//物流公司
		$logistic = $this -> _api -> getLogistic();
		$this -> view -> logisticList = $logistic;
	}
	
	/**
	 * ajax 操作快递单不连续处理
	 * 
	 */
	public function breakNoDoAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$tj = $this -> _request -> getParam('tj', '');
		$breakno = $this -> _request -> getParam('breakno', '');
		$batch = $this -> _request -> getParam('batch', '');
		$newstartno = (double)$this -> _request -> getParam('newstartno', 0);
		if($tj == ''){exit('err');}
		// $tj = 得到某单
		if($tj == 'getoneorder'){
			if($breakno == '' || $batch == ''){exit('err');}
			$w = $this -> _request -> getParam('ww', 0);
			if($w!=1 && $w!=2){exit('err');}
			$rs = $this -> _api -> getOutTuanOrder(array('logistics_no'=>$breakno, 'batch'=>$batch));
			if($rs['tot']<1){exit('nofind');}
			if($w == 1){//直接输出
				echo json_encode($rs['datas'][0]);
			}else{//找到下一条
				$rss = $this -> _api -> getOutTuanOrder(array('id'=>($rs['datas'][0]['id']-1), 'batch'=>$batch));
				if($rss['tot']<1){exit('nofind');}
				echo json_encode($rss['datas'][0]);
			}
			exit;
		}
		//更新
		if($tj == 'setneworder'){
			if($newstartno < 1 || $batch == ''){exit('err1');}
			$id = (int)$this -> _request -> getParam('id', 0);
			if($id<1){exit('err2');}
			$logistic = $this -> _request -> getParam('logistic', '');
			if($logistic==''){exit('err4');}
			//
			$rs = $this -> _api -> getOutTuanOrder(array('batch'=>$batch,'ischeat'=>'off'),1,1,null,1);
			$rs = $rs['datas'];
			//echo '<pre>';print_r($rs);exit();
			if(is_array($rs) && count($rs)){
				foreach ($rs as $v){
					if($id >= $v['id']){
						$this -> _api -> orderEdit(array('logistics_no'=>$newstartno,'logistics_com'=>$logistic),'id='.$v['id']);
						$newstartno+=1;
					}
				}
			}
			exit('ok');
		}
		exit('err3');
	}
	
	/**
	 * 团购订单查询（和orderAction一样，就是查询功能，没有操作按钮）
	 * 
	 * 
	 */
	public function orderListAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$datas = $this -> _api -> getOutTuanOrder($search, $page, 25);//dump($datas,1);
		$this -> view -> datas = $datas['datas'];
		$this -> view -> totalPrice = $datas['totalPrice'];
		$this -> view -> totalSupplyPrice = $datas['totalSupplyPrice'];
		$this -> view -> totalFee = $datas['totalFee'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($datas['tot'], 25, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(null,1,1,null,1);
		$this -> view -> shops = $shops['datas'];
		//订单导入人
		$add_user = $this -> _api -> getAddUser();
		$this -> view -> add_user = $add_user;
		//物流公司
		$logistic = $this -> _api -> getLogistic();
		$this -> view -> logisticList = $logistic;
	}
	
	/**
	 * ajax 设 刷单为 “已销账”
	 * 
	 */
	public function setIscheatClearAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$order_id = $this -> _request -> getParam('orderid', '');
		$price= $this -> _request -> getParam('price', 0);
		if($order_id == ''){exit('请选择');}
		$ids = explode(',', $order_id);
		if(is_array($ids) && count($ids)==1){
			//更新shop_out_tuan_order.ischeat状态
			$ischeatclearcfg = serialize(array('price'=>$price, 'op_name'=>$this -> _auth['real_name'], 'op_time'=>time()));
			$this -> _api -> orderEdit(array('ischeat'=>2, 'cheat_time'=>time(), 'ischeatclearcfg'=>$ischeatclearcfg),' id='.$ids[0]);
		}elseif(is_array($ids) && count($ids)>1){
			foreach ($ids as $v){
			    $detail = $this -> _api -> getOutTuanOrder(array('id'=>$v),1,1);
				$order= array_shift($detail['datas']);
				$ischeatclearcfg = serialize(array('price'=>$order['order_price'], 'op_name'=>$this -> _auth['real_name'], 'op_time'=>time()));
				$this -> _api -> orderEdit(array('ischeat'=>2, 'cheat_time'=>time(), 'ischeatclearcfg'=>$ischeatclearcfg),' id='.$v);
			}
		}else{exit('请选择');}
		exit('ok');
	}
	
	/**
	 * 团购刷单销账
	 * 
	 */
	public function isCheatOrderAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$search['ischeat'] = 'on';
		$datas = $this -> _api -> getshopOutTuanOrder($search, $page, 25);

		$this -> view -> datas = $datas['datas'];
		$this -> view -> totalPrice = $datas['totalPrice'];
		$this -> view -> totalSupplyPrice = $datas['totalSupplyPrice'];
		$this -> view -> totalFee = $datas['totalFee'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($datas['tot'], 25, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(null,1,1,null,1);
		$this -> view -> shops = $shops['datas'];
	}
	
	/**
	 * ajax 设置所有刷单为“已经销账”
	 * 
	 */
	public function setAllIscheatClearAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		//更新shop_out_tuan_order.ischeat状态
		$ischeatclearcfg = serialize(array('price'=>'系统批量设置', 'op_name'=>$this -> _auth['real_name'], 'op_time'=>time()));
		$this -> _api -> orderEdit(array('ischeat'=>2, 'ischeatclearcfg'=>$ischeatclearcfg),' ischeat=1');
		exit('ok');
	}
	
	/**
	 * 团购订单查询（和orderAction一样，就是查询功能，没有操作按钮）
	 * 
	 * 
	 */
	public function orderRefundAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$search['logistics'] = 'on';
		$search['print'] = 'on';
		$search['isback'] = 'off';
		$search['ischeat'] = 'off';
		$datas = $this -> _api -> getOutTuanOrder($search, $page, 25);//dump($datas,1);
		$this -> view -> datas = $datas['datas'];
		$this -> view -> totalPrice = $datas['totalPrice'];
		$this -> view -> totalSupplyPrice = $datas['totalSupplyPrice'];
		$this -> view -> totalFee = $datas['totalFee'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($datas['tot'], 25, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
		//团购网站
		$shops = $this -> _api -> getOutTuanShop(null,1,1,null,1);
		$this -> view -> shops = $shops['datas'];
		//订单导入人
		$add_user = $this -> _api -> getAddUser();
		$this -> view -> add_user = $add_user;
		//物流公司
		$logistic = $this -> _api -> getLogistic();
		$this -> view -> logisticList = $logistic;
	}
	
	/**
	 * 列表-同步批次订单到官网
	 * 
	 * 批次列表
	 */
	public function tongbuAction() {
		
	    $page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		
		$datas = $this -> _api -> getBatch($search, $page, 15);
		$this -> view -> datas = $datas['datas'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($datas['tot'], 15, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
		
	}
	
	/**
	 * ajax 同步订单到官网DO
	 * 
	 * 
	 */
	public function tongbuDoAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch);
		if($batch==''){exit('无此批次订单');}
		$rs = $this -> _api -> tongbuDo($batch);
		exit($rs);
	}
	
	/**
	 * 同步
	 * 计划任务调用
	 * 程序选择一个“已经发货&&没有同步”的批次
	 * 然后调用 $this -> _api -> tongbuDo($batch); 进行同步
	 * 
	 * @deprecated
	 */
	public function scheduleTongbuAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$datas = $this -> _api -> getBatch(array('logistics'=>'on','tongbu'=>'off','ts'=>5), 1, 1);
		if($datas['tot'] > 0){
			$rs = $this -> _api -> tongbuDo($datas['datas'][0]['batch']);
			exit($rs);
		}
	}
	
	/**
	 * 按批次修正以前为0金额的订单
	 * 
	 */
	public function modifyOrderPriceEqZeroAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$rs = $this -> _api -> modifyOrderPriceEqZero($batch);
		exit($rs);
	}
	
	/**
	 * ajax 重置批次同步状态   shop_order_batch.tongbu = 0||1
	 * 
	 */
	public function tongbuResetAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$s = $this -> _request -> getParam('s', 0);
		$batch = trim($batch);
		if($batch==''){exit('无此批次订单');}
		$rs = $this -> _api -> tongbuReset($batch, $s);
		exit($rs);
	}
	
	/**
	 * ajax整理那些没有同步的订单
	 * 
	 */
	public function tongbuClearAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$rs = $this -> _api -> batchUpdate(array('tongbu_time'=>'','tongbu_admin'=>'')," tongbu=0 ");
		exit('ok');
	}
	

	/**
	 * ajax 单个删除同步订单
	 * 
	 * @param string $sn
	 * 
	 * @deprecated   订单已经修正，不要再用了
	 */
	public function deleteTongbuOrderAction() {
		exit('禁止');
		$this -> _helper -> viewRenderer -> setNoRender();
		$sn = $this -> _request -> getParam('sn', '');
		if($sn == ''){ exit('没有订单号');}
		$rs = $this -> _api -> deleteTongbuOrder($sn);
		exit($rs);
	}
	
	/**
	 * 修正时间，以前同步的批次时间不对
	 * 
	 * @deprecated
	 */
	public function modifyTimeAction() {
		exit('禁用');
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch);
		if($batch == ''){ exit('没有批次号');}
		$rs = $this -> _api -> modifyTime($batch);
		exit($rs);
	}
	
	/**
	 * 修正 表
	 * shop_order_batch_goods字段：product_id、goods_id
	 * shop_outstock_detail字段：product_id
	 * 
	 * 插入表
	 * shop_report_product_sale
	 */
	public function modifyGidPidAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch);
		if($batch == ''){ exit('没有批次号');}
		$rs = $this -> _api -> modifyGidPid($batch);
		exit($rs);
	}
	
	/**
	 * 按搜索条件导出订单
	 * 
	 */
	public function exportOrderAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$datas = $this -> _api -> getOutTuanOrder($search, 1, 1, null, 1);
		if($datas['datas'])foreach ($datas['datas'] as $k=>$v){
			$datas['sortdatas'][$v['goods_id']][] = $v;
			$datas['tj'][$v['goods_id']]['ct'] += 1;
			$datas['tj'][$v['goods_id']]['goods_name'] = $v['goods_name'];
		}
		$this -> _api -> exportOrder($datas);
	}
	
	/**
	 * 插入 shop_out_tuan_batch
	 * 
	 */
	public function addBatchAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch);
		if($batch == ''){ exit('没有批次号');}
		$row = array('batch'=>$batch, 'logistics'=>'1', 'ctime'=>'1338456218');
		$rs = $this -> _api -> addBatch($row);
		exit($rs);
	}
	
	/**
	 * 修正 shop_order_batch_goods.cat_id 表
	 * 
	 */
	public function modifyCatidAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$rs = $this -> _api -> modifyCatID();
		exit($rs);
	}
	
	/**
	 * 同步 刷单订单
	 * 
	 */
	public function tongbuShuadanAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$rs = $this -> _api -> tongbuShuadan();
		exit($rs);
	}
	
	/**
	 * 重置批次发货状态
	 * 
	 */
	public function batchSendResetAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$s = $this -> _request -> getParam('s', 0);
		$batch = trim($batch);
		$rs = $this -> _api -> batchSendReset($batch, $s);
		exit($rs);
	}
	
	/**
	 * 更新批次订单的下单时间
	 * 
	 */
	public function updateOrderTimeAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$ts = $this -> _request -> getParam('ts', '');
		$batch = trim($batch);
		$ts = trim($ts);
		$ts = (int)$ts;
		$rs = $this -> _api -> updateOrderTime($batch, $ts);
		echo $rs;
		exit;
	}
	
	/***************/
	//  shop_out_tuan_batch.check_stock 字段含义：0：未检查库存，1：部分订单通过，2：检查所有订单通过 
	//  shop_out_tuan_order.check_stock 字段含义：0：未检查库存，1：检查通过
	/***************/
	
	/**
	 * 库存验证  批次列表
	 * 
	 */
	public function checkStockAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$search['check_stock'] = 'lt2';
		$search['tongbu'] = 'off';
		$datas = $this -> _api -> getBatch($search, $page, 15);
		$this -> view -> datas = $datas['datas'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($datas['tot'], 15, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
	}
	
	/**
	 * 验证库存开始 批次
	 * 
	 */
	public function checkStockDoAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch); if($batch === ''){exit('请输入批次号');}
		$rs = $this -> _api -> checkStockDo($batch);
		echo $rs; exit;
	}
	
	/**
	 * 删除批次
	 * 
	 */
	public function batchDelAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch);if($batch === ''){exit('请输入批次号');}
		$rs = $this -> _api -> batchDel($batch);
		echo $rs; exit;
	}
	
	/**
	 * 改变库存状态
	 * 
	 */
	public function changeBatchStockAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$st = (int)$this -> _request -> getParam('st', 0);
		$batch = trim($batch);if($batch === ''){exit('请输入批次号');}
		if(!in_array($sr, array(0,1,2))){exit('请输入库存检查状态');}
		$this -> _api -> batchUpdate(array('check_stock'=>$st), " batch=".$batch);
		echo 'ok'; exit;
	}
	
	/**
	 * 更新同步到官网的重复订单号（产生这个的原因是api中genSN()函数产生的订单号重复[已修复]）
	 * 
	 */
	public function modifyRepeatOrderAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$this -> _api -> modifyRepeatOrder();
		exit;
	}
	
	/**
	 * 更改订单的减库存状态
	 * 
	 */
	public function checkStockChangeAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', 0);
		$st = (int)$this -> _request -> getParam('st', 0);
		if($id<1){echo 'id不正确';exit;}
		if(!in_array($st, array(0,1))){echo 'st不正确';exit;}
		$this -> _api -> checkStockChange($id, $st);
		exit('ok');
	}
    
    /**
     * 根据'批次'来修正：
     * 1.表 shop_out_tuan_batch.xz 字段作为标示
     * 2.表中物流公司为空
     * 3.order_batch/transport表中运单号为空   transport表中物流公司为空
     
    public function xzAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$this -> _api -> xz();
    	exit;
    }*/
    
    /**
     * 新批次订单打印列表
     * 
     */
    public function newPrintlistAction() {
    	$page = (int)$this -> _request -> getParam('page', 1);
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
		$batch = isset($search['batch']) ? $search['batch'] : false;
		$batchs = $this -> _api -> getBatch(array('logistics'=>'lt2', 'tongbu'=>'off', 'check_stock'=>'gt0', 'batch'=>$batch), $page, 15);
		$this -> view -> datas = $batchs['datas'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($batchs['tot'], 15, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> param = $this -> _request -> getParams();
    }
    
    /**
     * 新批次订单打印页面
     * 
     */
    public function newBatchPrintAction() {
        
    	$batch = $this -> _request -> getParam('batch', '');
    	$batch = trim($batch); if($batch == ''){ exit('请输入批次号'); }
    	$datas = $this -> _api -> newBatchPrintData($batch);
    	$this -> view -> logistics_com_tot = count($datas['pcount']);
    	//ems快递单不能连号，所有单独拿出来打印
    	if(isset($datas['datas']['ems']) && count($datas['datas']['ems'])){
    		$this -> view -> emsOrders = $datas['datas']['ems'];
    		$this -> view -> emsOrdersCount = $datas['pcount']['ems']['ct'];
    		unset($datas['pcount']['ems']);
    	}
    	
    	$this -> view -> batch = $batch;
    	$this -> view -> datas = $datas['pcount'];
    	$this -> view -> orderTot = $datas['tot'];
    	
    }
    
    /**
     * 重新打印
     * 
     */
    public function newReprintAction() {
    	$batch = $this -> _request -> getParam('batch', '');
    	$batch = trim($batch); if($batch == ''){ exit('请输入批次号'); }
    	$datas = $this -> _api -> newBatchPrintData($batch, true);
    	$this -> view -> logistics_com_tot = count($datas['pcount']);
    	//ems快递单不能连号，所有单独拿出来打印
    	if(isset($datas['datas']['ems']) && count($datas['datas']['ems'])){
    		$this -> view -> emsOrders = $datas['datas']['ems'];
    		$this -> view -> emsOrdersCount = $datas['pcount']['ems']['ct'];
    		unset($datas['pcount']['ems']);
    	}
    	$this -> view -> batch = $batch;
    	$this -> view -> datas = $datas['pcount'];
    	$this -> view -> orderTot = $datas['tot'];
    	$this -> render('new-batch-print');
    }
    
    /**
     * 新批次订单打印
     */
    public function newPrintsAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$reprint = $this -> _request -> getParam('reprint', 'no');$reprint = trim($reprint);
		$reprint = ($reprint == 'yes') ? true : false;
		$logistics_com = $this -> _request -> getParam('logistics_com', '');
		$batch = trim($batch);if($batch == ''){exit('没有批次号');}
		$logistics_com = trim($logistics_com);if($logistics_com == ''){exit('没有快递公司');}
		
		//
		$rs = $this -> _api -> newBatchPrintData($batch, $reprint);

		if(!is_array($rs['datas'][$logistics_com]) || !count($rs['datas'][$logistics_com])){exit('批次'.$batch.'没有对应'.$logistics_com.'的订单');}
		$rs = $rs['datas'][$logistics_com];
		
		//dump($rs,1);
		//整理数据，和"快递单模板"相匹配
		foreach ($rs as $k=>$v){
			$rs[$k]['consignee'] = $v['name'];
			$rs[$k]['tel'] = $v['phone'];
			$rs[$k]['address'] = $v['addr'];
			$rs[$k]['bill_no'] = $v['order_sn'];
			$rs[$k]['print_remark'] = $v['batch'].'<br>'.$v['remark'];
			$rs[$k]['goods_number'] = $v['amount'];
		}
		//dump($rs,1);
		$logisticAPI = new Admin_Models_API_Logistic();
		$template = $logisticAPI -> getLogisticTemplate($logistics_com, 2);
		if ( !$template ) { die('找不到对应模板!'); }
		foreach ($rs as $num => $data){
			if($data['shop_name'] == 'QQ团购'){
				$data['brand'] = $data['company'] = '农民巴巴网';
				$data['sender_tel'] = '4007115116';
			}
	        $templates[] = $logisticAPI -> parseTemplate($template, $data);
        }
        $this -> view -> templates = $templates;
		echo $this -> view -> render('transport/print2.tpl');
		exit;
    }
    
    /**
     * 新批次销售单打印
     */
    public function saleBillPrintsAction() {

        $batch = $this -> _request -> getParam('batch', '');
		$logistics_com = $this -> _request -> getParam('logistics_com', '');
		$batch = trim($batch);if($batch == ''){exit('没有批次号');}
		$logistics_com = trim($logistics_com);if($logistics_com == ''){exit('没有快递公司');}
		$rs = $this -> _api -> newBatchPrintData($batch, true);
		
		if(!is_array($rs['datas'][$logistics_com]) || !count($rs['datas'][$logistics_com])){exit('批次'.$batch.'没有对应'.$logistics_com.'的订单');}
		$rs = $rs['datas'][$logistics_com];
		$logistic_name = $this->_api->getLgcNameByLgcCom($logistics_com);
		$bill = array();
		$conf_invo = array('无','个人','企业');
		foreach ($rs as $k=>$v){
		    $item = array();
		    //基本信息
		    $item['order_price'] = $v['order_price'];
		    $item['shop_name'] = $v['shop_name'];
		    $item['order_sn'] = $v['order_sn'];
		    $item['stc_price'] = $v['supply_price'];
			
			//发票信息
			$invoice_type = intval($v['invoice_type']);
			$invoice_taitou = ($invoice_type == 2) ? '('.$v['invoice_content'].')' : ''; 
		    $item['invoice_info'] = $conf_invo[$invoice_type].$invoice_taitou;
		    //配送信息
		    $item['ship']['logistic_name'] = $logistic_name;
		    $item['ship']['logistics_no'] = $v['logistics_no'];
		    $item['ship']['name'] = $v['name'];
		    $item['ship']['phone'] = $v['phone'];
		    $item['ship']['mobile'] = $v['mobile'];
		    $item['ship']['addr'] = $v['addr'];
		    $item['ship']['fee'] = $v['fee'];
		    //产品信息
		    $item['product']['sn'] = $v['goods_sn'];
		    $item['product']['name'] = $v['goods_name'];
		    $item['product']['style'] = $v['g_style'];
		    $item['product']['amount'] = $v['amount'];
		    $item['product']['supply_price'] = $v['supply_price'];
		    $item['product']['sale_price'] = $v['order_price']/$v['amount'];
		    $bill[] = $item;
		}
        $this -> view -> datas = $bill;
        
    }
    
    /**
     * 新批次填充快递单
     * 
     */
    public function newFillnoAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$logistics_com = $this -> _request -> getParam('lc', '');
		$first_no = (double)$this -> _request -> getParam('first_no', 0);
		$re= $this -> _request -> getParam('re', '');
		$batch = trim($batch); if($batch == ''){exit('批次号不正确');}
		$logistics_com = trim($logistics_com); if($logistics_com == ''){exit('快递公司代码不正确');}
		$re = trim($re); if($re == ''){exit('缺少手选快递公司代码');}
		$this -> _api -> newFillno($batch, $logistics_com, $first_no, $re);
		exit();
    }
    
    /**
     * 行填写ems快递单号
     * 
     */
    public function newFillemsnoAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
		$no = $this -> _request -> getParam('no', ''); $no = trim($no);
		$batch = $this -> _request -> getParam('batch', ''); $batch = trim($batch);
		if($id < 1){ exit('ID错误'); }
		if($no == ''){ exit('请输入ems快递单号'); }
		if($batch == ''){ exit('请输入批次号'); }
		//填ems单号
		$set=array('logistics'=>1, 'print'=>1, 'logistics_com'=>'ems', 'logistics_no'=>$no, 'logistics_time'=>time(), 'print_user'=>$this -> _auth['admin_id']);
		$this -> _api -> orderEdit($set, "id = $id");
		//检查shop_out_tuan_batch 表
		$this -> _api -> newCheckBatchLogistics($batch);
		//
    	exit('ok');
    }
    
    /**
     * 检查此批次的打印状况
     * 
     */
    public function newCheckBatchPrintStatusAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch);
		if($batch == ''){exit('请输入批次号');}
		//检查shop_out_tuan_batch 表
		$this -> _api -> newCheckBatchLogistics($batch);
		//检查批次‘可打印’的订单是否已经全部打印
		$rs = $this -> _api -> newBatchPrintData($batch);
		if($rs){echo 'has';}
		exit();
    }
    
    /**
     * 新不连续快递单处理
     * 
     */
    public function newBreakNoAction() {
		$tj = $this -> _request -> getParam('tj', '');
		// $tj = 得到某单
		if($tj == 'findoneorder'){
			$this -> _helper -> viewRenderer -> setNoRender();
			$batch = $this -> _request -> getParam('batch', ''); $batch = trim($batch);
	    	$breakno = $this -> _request -> getParam('breakno', ''); $breakno = trim($breakno);
			$newstartno = $this -> _request -> getParam('newstartno', ''); $newstartno = trim($newstartno);
			if($breakno == '' || $batch == ''){exit('err');}
			$w = $this -> _request -> getParam('ww', 0);
			if($w!=1 && $w!=2){exit('err');}
			//断裂单号
			$bk = $this -> _api -> getOutTuanOrder(array('logistics_no'=>$breakno, 'batch'=>$batch));
			if($bk['tot']<1){exit('nofind');}
			$bk = $bk['datas'][0];
			if($w == 1){
				echo json_encode($bk);
			}
			//找到属于这个物流公司的下一条
			else{
				$search = array('batch'=>$batch, 'ischeat'=>'off');
				$o = $this -> _api -> newGetOutTuanOrder($search);
				if($o == 'nofind'){exit('nofind');}
				//取出这个物流公司的单子
				$o = $o['datas'][$bk['logistics_com']];
				//找到 $bk 的下一条
				$next = null;
				$flag = 0;
				foreach ($o as $v){
					if($flag == 1){ $next = $v; break;}
					if($v['id']==$bk['id'] && $v['logistics_no']==$bk['logistics_no']){
						$flag = 1;
					}
				}
				echo json_encode($next);
			}
			exit;
		}
		//填充
    	if($tj == 'fillbreakorder'){
    		$this -> _helper -> viewRenderer -> setNoRender();
    		$batch = $this -> _request -> getParam('batch', ''); $batch = trim($batch);
	    	$breakno = $this -> _request -> getParam('breakno', ''); $breakno = trim($breakno);
			$newstartno = $this -> _request -> getParam('newstartno', ''); $newstartno = trim($newstartno);
			//echo json_encode(array($batch, $breakno, $newstartno));exit;
			if($breakno == '' || $batch == '' || $newstartno==''){exit('err');}
			//断裂单号
			$bk = $this -> _api -> getOutTuanOrder(array('logistics_no'=>$breakno, 'batch'=>$batch));
			if($bk['tot']<1){exit('nofind');}
			$bk = $bk['datas'][0];
			//
			$search = array('batch'=>$batch, 'ischeat'=>'off');
			$o = $this -> _api -> newGetOutTuanOrder($search);
			if($o == 'nofind'){exit('nofind');}
			//取出这个物流公司的单子
			$o = $o['datas'][$bk['logistics_com']];
			//找到 $bk 断裂单号后面的所有单
			$flag = 0;
			$newstartno = (float)$newstartno;
			foreach ($o as $v){
				if($flag == 1){
					//更新订单的单号
					$set = array('logistics_com'=>$bk['logistics_com'], 'logistics_no'=>$newstartno);
					$this -> _api -> orderEdit($set, "id=".$v['id']);
					$newstartno += 1;
				}else{
					if($v['id']==$bk['id'] && $v['logistics_no']==$bk['logistics_no']){
						$flag = 1;
					}
				}
			}
			echo 'ok';
			//
			exit;
    	}
    }
    
    /**
     * 修正批次同步过缺少logistic_name、logistic_code的情况
     * 
     */
    public function batchLogisticComCodeAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch);
		if($batch == ''){exit('请输入批次号');}
		$rs = $this -> _api -> batchLogisticComCode($batch);
		echo $rs;
		exit;
    }
    
    /**
     * 按批次删除重复同步的订单
     * 
     */
    public function delTongbuRepeatOrderAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch);
		if($batch == ''){exit('请输入批次号');}
		$rs = $this -> _api -> delTongbuRepeatOrder($batch);
		echo $rs;
		exit;
    }
    
    /**
     * 改变批次物流公司代码logistics_com
     * 
     */
    public function changeBatchLogisticsComAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
		$batch = $this -> _request -> getParam('batch', '');
		$batch = trim($batch);
		if($batch == ''){exit('请输入批次号');}
		$flc = $this -> _request -> getParam('flc', '');
		$flc = trim($flc);
		if($flc == ''){exit('请输入原物流公司代码');}
		$nlc = $this -> _request -> getParam('nlc', '');
		$nlc = trim($nlc);
		if($nlc == ''){exit('请输入现物流公司代码');}
		$rs = $this -> _api -> changeBatchLogisticsCom($batch, $flc, $nlc);
		echo $rs;
		exit;
    }
    
    /**
     * 修改同步后有的订单调整金额没加
     * 
     */
    public function modifyPayAdjustAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$rs = $this -> _api -> modifyPayAdjust();
    	echo $rs;
    	exit;
    }

    /**
     * 修正批次16502180939实际出库数量，并stock_log
     * 已修正完成，禁用
     */
    public function modifyBatchStockAction(){
        exit;
        $this -> _helper -> viewRenderer -> setNoRender();
        $this -> _api -> modifyBatchStock();
        exit;
    }

    /**
     * 检测批次订单是否已全部填充单号
     */
    public function checkIsBillFilledAction(){
        $batch_no = $this->_request->getParam('batch_no');
        $lgc_com = $this->_request->getParam('logistics_com');
        
        if($this->_api->checkIsBillFilled($batch_no,$lgc_com)){
            die('Y');
        }
        die('N');
    }

	/**
	 * 根据产品编码获取产品信息
	 *
	 * @return    array
	 **/
	public function getAjaxPriceAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$product_sn = $this->_request->getParam('product_sn', '');
		$type       = $this->_request->getParam('type', 0);

		if (empty($product_sn)) {
			exit(json_encode(array('success' => 'false', 'message' => '产品SN不正确')));
		}

		if ($type == '1') {
			$product_db = new Admin_Models_DB_Product();
			$product_info = $product_db->getProductInfoByProductSn($product_sn);
		}

		if ($type == '2') {
			$group_db = new Admin_Models_DB_GroupGoods();
			$product_info = $group_db->getGroupInfoByGroupSn($product_sn);
		}

		if (empty($product_info)) {
			exit(json_encode(array('success' => 'false', 'message' => '没有相关产品数据')));
		}

		exit(json_encode(array('success' => 'true', 'message' => '操作成功', 'data' => $product_info)));
	}
    
	/**
     * 团购结款记录处理
     *
     * @return void
     */
	function dotuanAction()
	{
		$this -> _tuandb = Zend_Registry::get('db');
		$sql = "select shop_id,tids,clear_time from shop_order_external_clear where shop_id =11 ";
		$rs = $this -> _tuandb -> fetchAll($sql);
        foreach ($rs as $v){
		  $usql = " update shop_order_batch set clear_time = {$v['clear_time']} where order_id in({$v['tids']})";
          $this -> _tuandb -> execute($usql);
		}
		$sql = "select o.order_id,t.shop_id,t.order_sn,t.isclear ,t.clear_time from shop_out_tuan_order as t inner join shop_order  as o on t.order_sn = o.external_order_sn where  t.shop_id =11 and t.clear_time >0 ";
		$rslist = $this -> _tuandb -> fetchAll($sql);
        foreach ($rslist as $var){
		  $usql = " update shop_order_batch set clear_time = {$var['clear_time']} where order_id = {$var['order_id']} ";
           $this -> _tuandb -> execute($usql);
		}
		exit;
	}



}