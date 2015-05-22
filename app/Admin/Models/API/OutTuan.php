<?php

class Admin_Models_API_OutTuan extends Custom_Model_Dbadv
{
    /**
     * 
     * @var Admin_Models_DB_OutTuan
     */
	private $_db = null;
	
	/**
     * auth
     */
	private $_auth = null;
	
    /**
     * 上传xls路径
     */
	private $upPath = 'upload/tuan';
	
	/**
	 * SN 同步订单时保存订单号
	 */
	private $SNs = array();
	
	/**
	 * 
	 */
	private $outOrderUser = 'channel';
	private $outOrderUserID = 3;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_OutTuan();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}
	
	/**
	 * 得到外部团购合作网站名称
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * 
	 * @return array
	 */
	public function getOutTuanShop($search=null, $page=1, $pageSize=25, $exclude=null, $all=false) {
		$rs = $this -> _db -> getOutTuanShop($search, $page, $pageSize, $exclude, $all);
		foreach($rs['datas'] as $k=>$v){
			$rs['datas'][$k]['configs'] = unserialize($v['config']);
		}
		return $rs;
	}
	
	/**
	 * 得到外部团购商品
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * 
	 * @return array
	 */
	public function getOutTuanGoods($search=null, $page=1, $pageSize=25, $exclude=null, $all=false) {
		return $this -> _db -> getOutTuanGoods($search, $page, $pageSize, $exclude, $all);
	}
	
	/**
	 * 添加外部团购商品
	 * 
	 * @param array $arr
	 */
	public function goodsAdd($arr) {
		$insertID = $this -> _db -> goodsAdd($arr);
		//由取出此商品的goods_id 和 product_id
		//先判断编码是几位的
		if(strlen($arr['goods_sn']) == 9){
			$this -> setGroupGoods($arr['goods_sn'],$insertID);
		}elseif(strlen($arr['goods_sn']) == 8){
			$this -> setGIDPID($arr['goods_sn'],$insertID);
		}else{
			exit('?');
		}
	}
	
	/**
	 * 更新shop_out_tuan_goods表的g_id  p_id
	 * 
	 * @param string $sn
	 * @param int $goods_id  是shop_out_tuan_goods的goods_id
	 */
	public function setGIDPID($sn,$goods_id) {
		$g = $this -> _db -> getProductIDGoodsIDbySN($sn);	
		$this -> goodsEdit(array('p_id'=>$g['product_id'],'g_id'=>$g['goods_id'],'g_name'=>$g['product_name'],'g_style'=>$g['goods_style']), " goods_id=".$goods_id);
	}
	
	/**
	 * 子商品是组合商品时候 更新 shop_out_tuan_goods表的g_id  p_id
	 * 
	 * @param string $sn
	 * @param int $goods_id  是shop_out_tuan_goods的goods_id
	 */
	public function setGroupGoods($sn,$goods_id) {
		$groupGoodsDetail = $this -> _db -> getGroupGoodsBySN($sn);
		$this -> goodsEdit(array('p_id'=>$groupGoodsDetail['group_id'],'g_id'=>$groupGoodsDetail['group_id'],'g_name'=>$groupGoodsDetail['group_goods_name'],'g_style'=>$groupGoodsDetail['group_specification']), " goods_id=".$goods_id);
	}
	
	/**
	 * 修改外部团购商品
	 * 
	 * @param string $where
	 * @param array $set
	 */
	public function goodsEdit($set,$where) {
		$this -> _db -> goodsEdit($set,$where);
	}
	
	/**
	 * 删除
	 * 
	 * @param int $id
	 */
	public function goodsDel($id) {
		return $this -> _db -> goodsDel($id);
	}
	
	/**
	 * 导出商品表格
	 * 
	 * @param array $shops
	 */
	public function goodsExport($shops) {
		//生成表格
		$footer = '
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Unsynced/>
   <Print>
    <ValidPrinterInfo/>
    <PaperSizeIndex>9</PaperSizeIndex>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>4</ActiveRow>
     <ActiveCol>4</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
		$header = '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>垦丰电商 </Author>
  <LastAuthor>垦丰电商 </LastAuthor>
  <Created>2012-05-29T08:49:22Z</Created>
  <LastSaved>2012-05-30T00:59:49Z</LastSaved>
  <Company>垦丰电商 </Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>6870</WindowHeight>
  <WindowWidth>14955</WindowWidth>
  <WindowTopX>360</WindowTopX>
  <WindowTopY>90</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Center"/>
   <Borders/>
   <Font ss:FontName="宋体" x:CharSet="134" ss:Size="12"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s21">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
  </Style>
  <Style ss:ID="s22">
   <Interior ss:Color="#FF0000" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s23">
   <Interior ss:Color="#FF9900" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s24">
   <Interior/>
  </Style>
  <Style ss:ID="s25">
   <Interior ss:Color="#FFFF00" ss:Pattern="Solid"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Sheet1">
  <Table>
   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="55.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="178.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="86.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="112.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="75.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="39"/>
   <Column ss:Index="10" ss:AutoFitWidth="0" ss:Width="81.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="40.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="70.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="83.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="86.25"/>
   <Row ss:AutoFitHeight="0">
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"><Data ss:Type="String">SN</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">商品名称</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">数量/份</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">销售总份数</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">销售总数量</Data></Cell>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"><Data ss:Type="String">供货价</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">供货单价</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">供货价总额</Data></Cell>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"><Data ss:Type="String">团购销售价</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">团购销售单价</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">团购销售总额</Data></Cell>
   </Row>';
		$blank = '
   <Row ss:AutoFitHeight="0">
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
   </Row>';
		//构建内容
		$content = '';
		foreach ($shops as $k=>$v){
			$content .= $blank;
			$content .= '<Row ss:AutoFitHeight="0">
    <Cell ss:StyleID="s22"><Data ss:Type="String">'.$k.'</Data></Cell>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
   </Row>';
			if(is_array($v) && count($v)){
				foreach ($v as $kk=>$vv){
					$content .= '<Row ss:AutoFitHeight="0">
    <Cell ss:Index="2"><Data ss:Type="Number">'.$vv['goods_sn'].'</Data></Cell>
    <Cell><Data ss:Type="String">'.$vv['goods_name'].'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$vv['goods_number'].'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$vv['amount'].'</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="Number">'.$vv['amt'].'</Data></Cell>
    <Cell ss:StyleID="s24"/>
    <Cell ss:StyleID="s25"><Data ss:Type="Number">'.$vv['supply_price'].'</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="Number">'.$vv['suppleySinglePrice'].'</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="Number">'.$vv['amt']*$vv['suppleySinglePrice'].'</Data></Cell>
    <Cell ss:StyleID="s24"/>
    <Cell ss:StyleID="s24"><Data ss:Type="Number">'.$vv['goods_price'].'</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="Number">'.$vv['saleSinglePrice'].'</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="Number">'.$vv['amt']*$vv['saleSinglePrice'].'</Data></Cell>
   </Row>';
				}
			}
		}
		//dump($content,1,1);
		//输出
		$xls = $header.$content.$footer;
		header("Content-Type:application/vnd.ms-excel;charset=GB2312");
		header("Content-Disposition: inline; filename=\"团购商品统计分析.xls\"");
		echo stripslashes ($xls);
		exit;
	}
	
	/**
	 * 获取订单信息
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * @param bool $all  是否全选
	 * @param bool $idasc id升序排列
	 * 
	 * @return array
	 */
	public function getshopOutTuanOrder($search=null, $page=1, $pageSize=25, $exclude=null, $all=false, $idasc=false) {
		return $this -> _db -> getshopOutTuanOrder($search, $page, $pageSize, $exclude, $all, $idasc);
	}

	/**
	 * 得到外部团购订单
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * @param bool $all  是否全选
	 * @param bool $idasc id升序排列
	 * 
	 * @return array
	 */
	public function getOutTuanOrder($search=null, $page=1, $pageSize=25, $exclude=null, $all=false, $idasc=false) {
		$rs = $this -> _db -> getOutTuanOrder($search, $page, $pageSize, $exclude, $all, $idasc);
		foreach ($rs['datas'] as $k=>$v){
			$uns = unserialize($v['config']);
			$rs['datas'][$k]['label'] = $uns['code'];
			$rs['datas'][$k]['xiaozhang'] = unserialize($v['ischeatclearcfg']);
		}
		return $rs;
	}
	
	/**
	 * 添加团购订单
	 * 
	 * @param int $arr
	 */
	public function orderAdd($arr) {
		$order_time = strtotime($arr['order_time']);
		//$arr为每个字段对应xls的第x列
		$datas = $this -> readXls($arr['xpath']);
		array_shift($datas);
		$datasLength = count($datas);
		if(is_array($datas) && $datasLength){
			/*Start::订单重复检测*/
			//随即取5个订单，查询数据库进行检测
			for($ii=0;$ii<5;$ii++){
				$check = $this -> getOutTuanOrder(array('order_sn'=>$datas[rand(0, ($datasLength-1))][$arr['order_sn']]));
				if($check['tot'] > 0){return 'rp';}
			}
			/*End::订单重复检测*/
			//是否存在套餐
			$sub = $this -> getOutTuanGoods(array('goods_fid'=>$arr['mgoods_id']), 1, 1, null, 1);
			//有
			if($sub['tot']>0)
			{
				//得到团购商品信息
				$supGoods = $this -> getOutTuanGoods(array('goods_id'=>$arr['mgoods_id']));
				$supGoods = array_shift($supGoods['datas']);
				
				//Begin::检查表格
				// goods_type == 1 是否为（任意数量商品（多个）比如拉手A商品n个，B商品n个）
				if($supGoods['goods_type'] == 1){
					$cfg = array();
					foreach ($datas as $k=>$v){
						$subgoods = explode('/', $v[$arr['goods_name']]);
						$temp = array();//得到商品product_id和名称
						foreach ($sub['datas'] as $val){
							$temp[$val['p_id']] = $val;
						}
						foreach ($subgoods as $ka=>$va){
							if($va){
								foreach ($temp as $kb=>$vb) {
									if(strstr($va, $vb['goods_name'])){
										$t = explode(':', $va);
										$cfg[$ka]['p_id'] = $kb;
										$cfg[$ka]['amt'] = $t[1];
										break;
									}
								}
							}
						}
					}
					if(empty($cfg)){
						return 'errSubGoodsName';
					}
				}
				//goods_type != 1 正常商品or组合商品；
				else{
					//取出商品名称
					foreach ($sub['datas'] as $val){
						$subs[$val['goods_name']] = $val;
					}
					unset($sub);
					
					//检查子商品名称是否存在
					foreach ($datas as $k=>$v){
						if(!array_key_exists($v[$arr['goods_name']], $subs)){return 'errSubGoodsName';}
					}
				}
				//End::检查表格
				
				//$batch = $arr['mshop_id'].$arr['mgoods_id'].$arr['termid'].$arr['xpath'].date('mdHi');//网站+商品+xls+期数+月日时分
				$batch = $arr['termid'].date('mdHi');//期数+月日时分
				$ext = 1;
				foreach ($datas as $k=>$v){
					//
					if($supGoods['goods_type'] == 1){
						$cfg = array();
						//得到  goods_id amt    比如拉手的格式:夏威夷果250克:10 /杏仁250克:10 /
						$subgoods = explode('/', $v[$arr['goods_name']]);
						$temp = array();//得到商品product_id和名称
						foreach ($sub['datas'] as $val){
							$temp[$val['p_id']] = $val;
						}
						foreach ($subgoods as $ka=>$va){
							if($va){
								foreach ($temp as $kb=>$vb) {
									if(strstr($va, $vb['goods_name'])){
										$t = explode(':', $va);
										$cfg[$ka]['p_id'] = $kb;
										$cfg[$ka]['amt'] = $t[1];
										break;
									}
								}
							}
						}
						$goods_id = (count($cfg)>1) ? $supGoods['goods_id'] : $temp[$cfg[0]['p_id']]['goods_id'];
						$price = (count($cfg)>1) ? $supGoods['goods_price']*$v[$arr['amount']] : $temp[$cfg[0]['p_id']]['goods_price']*$v[$arr['amount']];
						$supply_price = (count($cfg)>1) ? $supGoods['supply_price']*$v[$arr['amount']] : $temp[$cfg[0]['p_id']]['supply_price']*$v[$arr['amount']];
						$fee = $price - $supply_price;
						$rmk = ($arr['remark']=='n') ? '' : $v[$arr['remark']];
						$remark = $v[$arr['goods_name']].$rmk;
						$amt = $v[$arr['amount']];
						$cfg = serialize($cfg);
						
					}else{
						if($subs[$v[$arr['goods_name']]]){
							$goods_id = $subs[$v[$arr['goods_name']]]['goods_id'];
							$price = $subs[$v[$arr['goods_name']]]['goods_price']*$v[$arr['amount']];
							$supply_price = $subs[$v[$arr['goods_name']]]['supply_price']*$v[$arr['amount']];
							$fee = $subs[$v[$arr['goods_name']]]['goods_price']*$subs[$v[$arr['goods_name']]]['rate']*$v[$arr['amount']]*0.01;
							$rmk = ($arr['remark']=='n')?'':$v[$arr['remark']];
							$remark = $subs[$v[$arr['goods_name']]]['goods_name'].$rmk;
							$amt = $v[$arr['amount']];
							$cfg = null;
						}
					}
					//$cheat = ($arr['ischeat']=='n')?0:$v[$arr['ischeat']];
					if($arr['ischeat']=='n'){
						$cheat = 0;
					}else{
						if(isset($v[$arr['ischeat']])){
							$cheat = 1;
						}else{
							$cheat = 0;
						}
					}
					//
					$tmp['shop_id'] = $arr['mshop_id'];
					$tmp['goods_id'] = $goods_id;
					$tmp['term_id'] = $arr['termid'];
					$tmp['xls_id'] = $arr['xpath'];
					$tmp['batch'] = $batch;
					$tmp['order_price'] = $price;
					$tmp['supply_price'] = $supply_price;
					$tmp['fee'] = $fee;
					$tmp['order_time'] = empty($order_time) ? time() : $order_time;

					if($v[$arr['buy_time']]){
					   $order_time = strtotime($v[$arr['buy_time']]);
					}else{
					   $order_time = strtotime($arr['order_time']);
					}

					$tmp['order_sn'] = $v[$arr['order_sn']];
					$tmp['order_goods_cfg'] = $cfg;
					$tmp['name'] = str_replace(',', '，', str_replace('\'', '', str_replace('"', '', $v[$arr['name']])));
					//处理电话，针对QQ团
					$lock = ($arr['phone']=='n')?$arr['mobile']:$arr['phone'];
					$dh = explode('，', $v[$lock]);
					if(count($dh)>1){
						$tmp['phone'] = $dh[1];
						$tmp['mobile'] = $dh[0];
					}else{
						$tmp['phone'] = ($arr['phone']=='n')?'':$v[$arr['phone']];
						$tmp['mobile'] = ($arr['mobile']=='n')?'':$v[$arr['mobile']];
					}
					//
					$tmp['postcode'] = ($arr['postcode']=='n')?'':$v[$arr['postcode']];
					$tmp['addr'] = str_replace(',', '，', str_replace('\'', '', str_replace('"', '', str_replace('n', '', $v[$arr['sheng']].$v[$arr['shi']].$v[$arr['qu']].$v[$arr['addr']]))));

					$tmp['addr'] = trim($tmp['addr']);
					$tmp['mobile'] = trim($tmp['mobile']);

					$tmp['remark'] = str_replace(',', '，', str_replace('\'', '', str_replace('"', '', $remark)));
					$tmp['amount'] = $amt;
					$tmp['logistics'] = $cheat?1:0;
					$tmp['print'] = $cheat?1:0;
					if($arr['logistics_com']!='n' || $arr['logistics_no']!='n'){
						$tmp['logistics_com'] = $v[$arr['logistics_com']];
						$tmp['logistics_no'] = $v[$arr['logistics_no']];
						$tmp['logistics'] = 1;
						$tmp['print'] = 1;
					}
					$tmp['add_user'] = $arr['add_user'];
					$tmp['comments'] = '';
					$tmp['status'] = 1;
					$tmp['ctime'] = time();
					$tmp['ischeat'] = $cheat;
					$tmp['check_stock'] = $cheat;
					$this -> _db -> orderAdd($tmp);
					$ext++;
					if($ext>200){
						//插入shop_out_tuan_batch表，这张表用来同步到官网订单
						$this -> _db -> batchAdd($batch);
						$batch = (float)$batch+1;
						$ext = 1;
					}
				}
			}
			else//无
			{
				//得到团购商品信息
				$goodsDetail = $this -> getOutTuanGoods(array('goods_id'=>$arr['mgoods_id']));
				$goodsDetail = $goodsDetail['datas'][0];
				//
				//$batch = $arr['mshop_id'].$arr['mgoods_id'].$arr['termid'].$arr['xpath'].date('mdHi');//网站+商品+xls+期数+月日时分
				$batch = $arr['termid'].date('mdHi');//期数+月日时分
				$ext = 1;
				foreach ($datas as $k=>$v){
					//$cheat = ($arr['ischeat']=='n')?0:$v[$arr['ischeat']];
					if($arr['ischeat']=='n'){
						$cheat = 0;
					}else{
						if(isset($v[$arr['ischeat']])){
							$cheat = 1;
						}else{
							$cheat = 0;
						}
					}
					$tmp['shop_id'] = $arr['mshop_id'];
					$tmp['goods_id'] = $arr['mgoods_id'];
					$tmp['term_id'] = $arr['termid'];
					$tmp['xls_id'] = $arr['xpath'];
					$tmp['batch'] = $batch;
					$tmp['order_price'] = $goodsDetail['goods_price']*$v[$arr['amount']];
					$tmp['supply_price'] = $goodsDetail['supply_price']*$v[$arr['amount']];
					$tmp['fee'] = $goodsDetail['goods_price']*$v[$arr['amount']]*$goodsDetail['rate']*0.01;
					$tmp['order_time'] = $order_time;
					$tmp['order_sn'] = $v[$arr['order_sn']];
					$tmp['name'] = str_replace(',', '，', str_replace('\'', '', str_replace('"', '', $v[$arr['name']])));
					//处理电话，针对QQ团
					$lock = ($arr['phone']=='n')?$arr['mobile']:$arr['phone'];
					$dh = explode('，', $v[$lock]);
					if(count($dh)>1){
						$tmp['phone'] = $dh[1];
						$tmp['mobile'] = $dh[0];
					}else{
						$tmp['phone'] = ($arr['phone']=='n')?'':$v[$arr['phone']];
						$tmp['mobile'] = ($arr['mobile']=='n')?'':$v[$arr['mobile']];
					}
					//
					$tmp['postcode'] = ($arr['postcode']=='n')?'':$v[$arr['postcode']];
					$tmp['addr'] = str_replace(',', '，', str_replace('\'', '', str_replace('"', '', str_replace('n', '', $v[$arr['sheng']].$v[$arr['shi']].$v[$arr['qu']].$v[$arr['addr']]))));
					$tmp['remark'] = ($arr['remark']=='n')?'':str_replace(',', '，', str_replace('\'', '', str_replace('"', '', $v[$arr['remark']])));
					$tmp['amount'] = $v[$arr['amount']];
					$tmp['invoice_type'] = $v[$arr['invoice_type']];
					$tmp['invoice_content'] = ($arr['invoice_content']=='n')?'':str_replace(',', '，', str_replace('\'', '', str_replace('"', '', $v[$arr['invoice_content']])));;
					$tmp['logistics'] = $cheat?1:0;
					$tmp['print'] = $cheat?1:0;
					if($arr['logistics_com']!='n' || $arr['logistics_no']!='n'){
						$tmp['logistics_com'] = $v[$arr['logistics_com']];
						$tmp['logistics_no'] = $v[$arr['logistics_no']];
						$tmp['logistics'] = 1;
						$tmp['print'] = 1;
					}
					$tmp['logistics_time'] = $order_time;
					$tmp['add_user'] = $arr['add_user'];
					$tmp['comments'] = '';
					$tmp['status'] = 1;
					$tmp['ctime'] = time();
					$tmp['ischeat'] = $cheat;
					$tmp['check_stock'] = $cheat;
					$this -> _db -> orderAdd($tmp);
					$ext++;
					if($ext>200){
						//插入shop_out_tuan_batch表，这张表用来同步到官网订单
						$this -> _db -> batchAdd($batch);
						$batch = (float)$batch+1;
						$ext = 1;
					}
				}
			}
			$this -> _db -> batchAdd($batch);
			//end
			return 'ok';
		}else{
			return 'err';
		}
	}
	
	/**
	 * 修改订单
	 * 
	 * @param string $where
	 * @param array $set
	 */
	public function orderEdit($set,$where) {
		$this -> _db -> orderEdit($set,$where);
	}
	
	/**
	 * 真正删除点的批次
	 * 
	 * @param float $batch
	 */
	public function deleteBatchOrder($batch) {
		$this -> _db -> deleteBatchOrder($batch);
	}
	
	/**
	 * 真正 删除单条订单
	 * 
	 * @param sring $order_id
	 */
	public function orderDelOne($order_id) {
		$this -> _db -> orderDelOne($order_id);
	}
	
	/**
	 * 添加客服备注
	 * 
	 * @param int $id
	 * @param int $real_name
	 * @param string $content
	 */
	public function commentAdd($id, $real_name, $content) {
		$content = $real_name.'&nbsp;&nbsp;&nbsp;'.date('Y-m-d H:i:s').'&nbsp;&nbsp;&nbsp;'.$content.'|||';
		$this -> _db -> commentAdd($id, $content);
	}
	
	/**
	 * 读取xls
	 * 
	 * @param string $id
	 * @param boolean $onlyGetHeader  只取xls文件头部
	 */
	public function readXls($id, $onlyGetHeader=false) {
		$detail = $this -> _db -> getOutTuanXls($id);
		if(is_array($detail) && count($detail)){
			//处理
			$xls = new Custom_Model_ExcelReader();
			$xls -> setOutputEncoding('utf-8');
			$xls -> read($detail['savepath']);
			$datas = $xls -> sheets[0];
			$datas = $datas['cells'];//得到xls数据
			if(is_array($datas) && count($datas)){
				if($onlyGetHeader){
					return array_shift($datas);
				}
				return $datas;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 添加xls记录
	 * 
	 * @param int $shop_id
	 * @param int $goods_id
	 * @param string $xlspath
	 * 
	 * @return int $insertID
	 */
	public function xlsAdd($shop_id,$goods_id,$xlspath) {
		return $this -> _db -> xlsAdd($shop_id,$goods_id,$xlspath);
	}
	
	/**
	 * 得到物流公司
	 * 
	 * @return array
	 */
	public function getLogistic() {
		return $this -> _db -> getLogistic();
	}
	
	/**
	 * 得到admin用户资料
	 * 
	 * @param int $admin_id
	 */
	public function getAdminUser($admin_id) {
		return $this -> _db -> getAdminUser($admin_id);
	}
			
	/**
	 * 导出批次excel
	 * 
	 * @param string $batch
	 */
	public function exportBatchOrder($batch) {
		header("Pragma:public");
		$rs = $this -> _db -> getOutTuanOrder(array('batch'=>$batch, 'ischeat'=>'off'),1,1,null,1,1);
		$rs = $rs['datas'];
		
		/*$doc[0] = array('网站', '商品名称', '数量', '订单时间', '订单号', '收件人','固定电话', '移动电话', '邮编', '地址', '备注', '快递公司', '快递单号');
		$k = 1;
		foreach ($rs as $v){
			$doc[$k][] = $this -> codeFilter($v['shop_name']);
			$doc[$k][] = $this -> codeFilter($v['goods_name']);
			$doc[$k][] = $this -> codeFilter($v['amount']);
			$doc[$k][] = $this -> codeFilter($v['order_time']);
			$doc[$k][] = $this -> codeFilter($v['order_sn']);
			$doc[$k][] = $this -> codeFilter($v['name']);
			$doc[$k][] = $this -> codeFilter($v['phone']);
			$doc[$k][] = $this -> codeFilter($v['mobile']);
			$doc[$k][] = $this -> codeFilter($v['postcode']);
			$doc[$k][] = $this -> codeFilter($v['addr']);
			$doc[$k][] = $this -> codeFilter($v['remark']);
			$doc[$k][] = $this -> codeFilter($v['logistics_com']);
			$doc[$k][] = $this -> codeFilter($v['logistics_no']);
			$k++;
		}*/
		$doc[0] = array('数量', '订单号', '收件人','手机', '邮编', '地址', '备注', '快递公司', '快递单号');
		$k = 1;
		foreach ($rs as $v){
			$doc[$k][] = $this -> codeFilter($v['amount']);
			$doc[$k][] = $this -> codeFilter($v['order_sn']);
			$doc[$k][] = $this -> codeFilter($v['name']);
			$doc[$k][] = $this -> codeFilter($v['mobile']);
			$doc[$k][] = $this -> codeFilter($v['postcode']);
			$doc[$k][] = $this -> codeFilter($v['addr']);
			$doc[$k][] = $this -> codeFilter($v['remark']);
			$doc[$k][] = $this -> codeFilter($v['logistics_com']);
			$doc[$k][] = $this -> codeFilter($v['logistics_no']);
			$k++;
		}
		//空行
		$doc[$k++][]='';
		//加几行
		$doc[$k][] = '';
		$doc[$k][] = '';
		$doc[$k][] = $rs[0]['shop_name'];
		$doc[$k++][] = $batch;
		//取出这个批次的汇总单
		$summary = $this -> batchPrintSummary($batch);
		if($summary[0]['summary'])foreach ($summary[0]['summary'] as $v){
			$doc[$k][] = '';
			$doc[$k][] = '';
			$doc[$k][] = '';
			$doc[$k][] = $v['ct'];
			$doc[$k][] = '';
			//$doc[$k][] = $v['sn'];
			//$doc[$k][] = $v['per_number'].'×'.$v['style'];
			$doc[$k++][] = $v['goods_name'];
		}
		//
		$xls = new Custom_Model_GenExcel();
		$xls->addArray($doc);
		$showname = $rs[0]['shop_name'].'-'.$rs[0]['goods_name'].'-'.$batch;
		if (preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])){
			$showname = urlencode($showname);
		}
		$xls->generateXML($showname);
		exit();
		
		/*
		$excel = new Custom_Model_Excel();
		$excel -> send_header($rs[0]['shop_name'].$rs[0]['goods_name'].'-'.date('m月d日').'.xls');
		$excel -> xls_BOF();
		//第一行
		$title = array('网站', '商品名称', '数量', '订单时间', '订单号', '收件人','固定电话', '移动电话', '邮编', '地址', '备注', '快递公司', '快递单号');
		$col = count($title);
		for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
        //
        foreach ($rs as $k=>$v){
        	$remark = $v['remark']=='NULL'?'':$v['remark'];
        	$row = array($v['shop_name'],$v['goods_name'],$v['amount'],$v['order_time'],$v['order_sn'],$v['name'],
        				$v['phone'],$v['mobile'],$v['postcode'],$v['addr'],$remark,$v['logistics_com'],$v['logistics_no']);
        	//$row = array(1,2,3,4,5,6,7,8,9,0,1,2,3);
			for ($i = 0; $i < $col; $i++) {
			    $excel -> xls_write_label($k+1, $i, $row[$i]);
			}
			flush();
		    ob_flush();
			unset($row);
        }
        unset($rs);
        $excel -> xls_EOF();
        */
	}
	
	/**
	 * 打印批次销售汇总单
	 * 
	 * @param int $batch
	 */
	public function batchPrintSummary($batch = '') {
		$batch = trim($batch);
		if($batch == ''){ exit('请输入批次号'); }
		$batchData = $this -> getBatch(array('batch'=>$batch));
		if($batchData['tot'] < 1){ exit('批次不存在'); }
		$batchData = array_shift($batchData['datas']);
		//如果有stock_batch_info，则按新的库存批次显示
		
		$apiStock = new Admin_Models_API_Stock();

		if($batchData['stock_batch_info']){
			$oneOrder = $this -> getOutTuanOrder(array('batch'=>$batch,'ischeat'=>'off'),1,1,null,1,1);
			if($oneOrder['tot'] < 1){ exit('此批次下没有订单'); }
			$oneOrder = array_shift($oneOrder['datas']);
			
			$tuanGoodsPrice = array();
			$tuanGoods = $this -> getOutTuanGoods(array('goods_id'=>$oneOrder['goods_id'], 'quan'=>1), 1, 1);
			$tuanGoods = array_shift($tuanGoods['datas']);
			if($tuanGoods['goods_fid']){
				$tuanGoods = $this -> getOutTuanGoods(array('goods_fid'=>$tuanGoods['goods_fid'], 'quan'=>1), 1, 1, null,1);
				$tuanGoods = $tuanGoods['datas'];
				foreach ($tuanGoods as $v){
					$tuanGoodsPrice[$v['p_id']] = $v['supply_price'];
				}
			}else{
				$tuanGoodsPrice[$tuanGoods['p_id']] = $tuanGoods['supply_price'];
			}
			
			$back = array();
			$back[0]['shop_name'] = $oneOrder['shop_name'];
			$back[0]['goods_name'] = $oneOrder['goods_name'];
			$back[0]['print_time'] = date('Y 年 m 月 d 日');
			$back[0]['batch'] = $batch;
			$back[0]['admin_name'] = $this -> _auth['admin_name'];
			$back[0]['totalPrice'] = $oneOrder['totalPrice'];
			$back[0]['totalSupplyPrice'] = $oneOrder['totalSupplyPrice'];
			$back[0]['totalSupplyPrice'] = $oneOrder['totalSupplyPrice'];
			
			$batchData['_stock_batck_info'] = unserialize($batchData['stock_batch_info']);
			$i = 0;
			foreach ($batchData['_stock_batck_info'] as $product_id=>$product_batch){
				$t = array();
				foreach ($product_batch as $pb=>$pn){
					$t[] = $pb.' : '.$pn['number'];
				}
				$t = implode('<br>', $t);
				//取得此商品
				$productDetail = $this -> _db -> getProduct(array('product_id'=>$product_id), 't1.product_name,t1.product_sn,t1.goods_style,t1.goods_units');
				$productDetail = array_shift($productDetail);
				$ct[$i]['goods_name'] = $productDetail['product_name'];
				$ct[$i]['ct'] = $t;
				$ct[$i]['sn'] = $productDetail['product_sn'];
				$ct[$i]['style'] = $productDetail['goods_style'];
				$ct[$i]['per_number'] = '';
				$ct[$i]['supply_price'] = $tuanGoodsPrice[$product_id];
				//$ct[$i]['order_price'] = $orderPrice;
				$i++;
			}
			$back[0]['summary'] = $ct;
		}
		//如果没有 stock_batch_info，则按以前的方式显示
		else{
						
			if($batch != ''){//如果有$batch
				$batch = str_replace('，', ',', $batch);
				$batch = explode(',', $batch);
				foreach ($batch as $v){
					$rs = $this -> getOutTuanOrder(array('batch'=>$v,'ischeat'=>'off'),1,1,null,1,1);
					if($rs['tot']>0){
						$batchAll[] = $rs;
					}
				}
			}else{//如果没有
				//取出所有没有打印发货的批次
				$batchs = $this -> getBatch(array('logistics'=>'off', 'tongbu'=>'off'), $page, 15);
				foreach ($batchs['datas'] as $k=>$v){
					$rs = $this -> getOutTuanOrder(array('batch'=>$v['batch'],'ischeat'=>'off'),1,1,null,1,1);
					if($rs['tot']>0){
						$batchAll[] = $rs;
					}
				}
			}
			
			//
			foreach ($batchAll as $k=>$v){
				//取出货位
				$product_sn = $v['datas'][0]['goods_sn'];
				$local_sn = $apiStock->getPositionInfosByProductSn($product_sn);
				$local_sn = (isset($local_sn[0]['local_sn']))? $local_sn[0]['local_sn'] : '';
				
				//
				$back[$k]['shop_name'] = $v['datas'][0]['shop_name'];
				$back[$k]['goods_name'] = $v['datas'][0]['goods_name'];
				$back[$k]['print_time'] = date('Y 年 m 月 d 日');
				$back[$k]['batch'] = $v['datas'][0]['batch'];
				$back[$k]['admin_name'] = $this -> _auth['admin_name'];
				$back[$k]['totalPrice'] = $v['totalPrice'];
				$back[$k]['totalSupplyPrice'] = $v['totalSupplyPrice'];
				$back[$k]['local_sn'] = $local_sn;
				
				foreach ($v['datas'] as $kk=>$vv){
					$tmp[$vv['goods_name']][] = $vv;
				}
				
				$i = 0;
				$ct = null;
				foreach ($tmp as $kk=>$vv){
					$tot = 0;
					foreach ($vv as $kkk=>$vvv){
						$tot += $vvv['amount'];
						$sn = $vvv['goods_sn'];
						$style = $vvv['g_style'];
						$perNumber = $vvv['goods_number'];
						$supplyPrice = $vvv['supply_price']/$vvv['amount'];
						$orderPrice = $vvv['order_price']/$vvv['amount'];
					}
					$ct[$i]['goods_name'] = $kk;
					$ct[$i]['ct'] = $tot;
					$ct[$i]['sn'] = $sn;
					$ct[$i]['style'] = $style;
					$ct[$i]['per_number'] = $perNumber;
					$ct[$i]['supply_price'] = $supplyPrice;
					$ct[$i]['order_price'] = $orderPrice;
					$i++;
				}
				$back[$k]['summary'] = $ct;
				
				$tmp = null;
			}
		}
		
		return $back;
	}
	
	/**
	 * 得到所有添加人
	 * 
	 */
	public function getAddUser() {
		$addUserId = $this -> _db -> getAddUserId();
		foreach ($addUserId as $k=>$v){
			$users[] = $this->getAdminUser($v['add_user']);
		}
		return $users;
	}
	
	/**
	 * 得到网站的团购商品
	 * 
	 * @param int $shopid
	 */
	public function getShopGoods($shopid) {
		return $this -> getOutTuanGoods(array('shop_id'=>$shopid,'status'=>'on'),1,1,null,1);
	}
	
	/**
	 * 得到期数
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $orderby
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * @param bool $all 取全部结果
	 * 
	 * @return array
	 */
	public function getTerm($search=null, $page=1, $pageSize=25, $orderby=null, $exclude=null, $all=false) {
		return $this -> _db -> getTerm($search, $page, $pageSize, $orderby, $exclude, $all);
	}
	
	/**
	 * 添加期数
	 * 
	 * @param array $arr
	 */
	public function termAdd($arr) {
		$this -> _db -> termAdd($arr);
	}
	
	/**
	 * 删除term
	 * 
	 * @param int $id
	 */
	public function termDelete($id) {
		return $this -> _db -> termDelete($id);
	}
	
	/**
	 * 修改期数
	 * 
	 * @param string $where
	 * @param array $set
	 */
	public function termEdit($set,$where) {
		$this -> _db -> termEdit($set,$where);
	}
	
	/**
	 * 结款单申请
	 * 
	 * $arr
	 */
	public function billAskAdd($arr) {
		$this -> _db -> billAskAdd($arr);
	}
	
	/**
	 * 得到结款单申请
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $orderby
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * @param bool $all 取全部结果
	 * 
	 * @return array
	 */
	public function getBill($search=null, $page=1, $pageSize=25, $orderby=null, $exclude=null, $all=false) {
		return $this -> _db -> getBill($search, $page, $pageSize, $orderby, $exclude, $all);
	}
	
	/**
	 * 审核结款申请单
	 * 
	 * @param array $arr
	 */
	public function billVerify($arr) {
		$this -> _db -> billVerify($arr);
	}
	
	/**
	 * 结算do
	 * 
	 * @param array $arr
	 */
	public function billClearDo($arr) {
		$this -> _db -> billClearDo($arr);
	}
	
	/**
	 * 收款纪录
	 * 
	 * @param array $arr
	 */
	public function addFinanceLog($arr) {
		$this -> _db -> addFinanceLog($arr);
	}
	
	/**
	 * 得到收款纪录
	 * 
	 * @param int $id
	 */
	public function getFinanceLog($id) {
		return $this -> _db -> getFinanceLog($id);
	}
	
	/**
	 * 锁定
	 * 
	 * @param array $set
	 * @param array $ids
	 */
	public function lockFinance($set,$ids) {
		foreach ($ids as $k=>$v){
			//检查是否已经被锁定
			$check = $this -> getBill(array('id'=>$v));
			if($check['tot']<1){exit('没有找到申请单');}
			if($check['datas'][0]['locker']==''){
				$tmp[] = $v;
			}
		}
		if(!$tmp){return 'noselect';}
		$ids = implode(',', $tmp);
		$this -> _db -> lockFinance($set," id in (".$ids.")");
	}
	
	/**
	 * 解锁
	 * 
	 * @param array $set
	 * @param array $ids
	 * @param string $realname
	 */
	public function unlockFinance($set,$ids,$realname) {
		foreach ($ids as $k=>$v){
			//检查是否已经被锁定
			$check = $this -> getBill(array('id'=>$v));
			if($check['tot']<1){exit('没有找到申请单');}
			if($check['datas'][0]['locker']==$realname){
				$tmp[] = $v;
			}
		}
		if(!$tmp){return 'noselect';}
		$ids = implode(',', $tmp);
		$this -> _db -> unlockFinance($set," id in (".$ids.")");
	}
	
	/**
	 * 重置lock
	 * 
	 * @param array $set
	 * @param string $where
	 */
	public function resetlock($set,$where) {
		$this -> _db -> resetlock($set,$where);
	}
	
	/**
	 * 更改期数订单的状态为已结款
	 * 
	 * @param array $set
	 * @param string $where 
	 */
	public function termOrderClear($set,$where) {
		$this -> _db -> termOrderClear($set,$where);
	}
	
	/**
	 * 得到商品的product_id
	 * 
	 * @param int $goods_sn
	 */
	public function getProductID($goods_sn) {
		return $this -> _db -> getProductID($goods_sn);
	}
	
	/**
	 * 插入 shop_instock
	 * 
	 * @param array $row
	 */
	public function instockAdd($row) {
		return $this -> _db -> instockAdd($row);
	}
	
	/**
	 * 插入 shop_instock_detail
	 * 
	 * @param array $row
	 */
	public function instockDetailAdd($row) {
		return $this -> _db -> instockDetailAdd($row);
	}
	
	/**
	 * 插入 shop_instock_plan
	 * 
	 * @param array $row
	 */
	public function instockPlanAdd($row) {
		return $this -> _db -> instockPlanAdd($row);
	}
	
	/**
	 * 得到instock记录
	 * 
	 * @param int $item_no
	 */
	public function getInstock($item_no) {
		return $this -> _db -> getInstock($item_no);
	}
	
	/**
	 * 删除instock 记录
	 * 
	 * @param int $instock_id
	 */
	public function instockDelete($instock_id) {
		$this -> _db -> instockDelete($instock_id);
	}
	
	/**
	 * 删除instock_detail 记录
	 * 
	 * @param int $instock_id
	 */
	public function instockDetailDelete($instock_id) {
		$this -> _db -> instockDetailDelete($instock_id);
	}
	
	/**
	 * 删除instock_plan 记录
	 * 
	 * @param int $instock_id
	 */
	public function instockPlanDelete($instock_id) {
		$this -> _db -> instockPlanDelete($instock_id);
	}
	
	/**
	 * 设置为取消单
	 * 
	 * @param int $instock_id
	 */
	public function instockCancel($instock_id) {
		$this -> _db -> instockCancel($instock_id);
	}
	
	/**
	 * 由团购订单ID得到退货的状态
	 * 
	 * @param int $order_id
	 */
	public function getReturnBillStatus($order_id) {
		$status = $this -> _db -> getReturnBillStatus($order_id);
		if($status == 7){ return 1;}
		else{ return 0;}
	}
	
	/**
	 * 得到选中期数的总额
	 * 
	 * @param string $terms  (1,2,3...)
	 * 
	 * @return decimal
	 */
	public function getTermsAmount($terms) {
		return $this -> _db -> getTermsAmount($terms);
	}
	
	/**
	 * 过滤 符号
	 * 
	 * @param string $str
	 * @param array $filter
	 * 
	 * @return string
	 */
	public function codeFilter($str, $filter=array('>','<')) {
		if(is_array($filter) && count($filter)){
			foreach ($filter as $v){
				$str = str_replace($v, '', $str);
			}
		}
		return $str;
	}
	
	/**
	 * 更新shop_out_tuan_batch
	 * 
	 * @param array $set
	 * @param string $where
	 */
	public function batchUpdate($set, $where) {
		$this -> _db -> batchUpdate($set, $where);
	}
	
	/**
	 * 得到shop_out_tuan_batch列表
	 * 
	 */
	public function getBatch($search=null, $page=1, $pageSize=25, $orderby=null, $exclude=null, $all=false) {
		return $this -> _db -> getBatch($search, $page, $pageSize, $orderby, $exclude, $all);
	}
	
	/* Start::外部团购订单同步到官网订单 */
	
	/**
	 * 产生 SN  不重复
	 */
	public function genSN() {
		$SN = Custom_Model_CreateSn::createExternalSn();
		if(in_array($SN, $this->SNs)){
			$this -> genSN();
		}else{
			$this -> SNs[] = $SN;
		}
		return $this -> SNs[count($this->SNs) - 1];
	}
	
	/**
	 * 同步订单到官网DO
	 * 
	 * @param string $batch
	 */
	public function tongbuDo($batch) {
		set_time_limit(60);
		$outOrderBatch = $this -> getOutTuanOrder(array('batch'=>$batch),1,1,null,1);
		
		if($outOrderBatch['tot']>0){
		    
			$outOrderBatch = $outOrderBatch['datas'];
			
			$outOrderBatchLength = count($outOrderBatch);
			
			//随机取出3个记录，检查shop_order是否存在"重复"的外部团购订单
			for($ii=0;$ii<3;$ii++){
				$ck = $outOrderBatch[rand(0, ($outOrderBatchLength-1))];
				$ck['order_sn'] = trim($ck['order_sn']);
				$ckRs = $this -> _db -> getOrderByExtentOrderSn($ck['order_sn']);
				if($ckRs){
					return '同步订单已经存在';
				}
			}
			
			//物流公司
			$logistic = $this -> getLogistic();
			
			
			foreach ($logistic as $index=>$val){
				$tmp[$val['logistic_code']] = $val['name'];
			}
			$logistic = $tmp; unset($tmp);
			
			//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/rs.txt', print_r($outOrderBatch,1)); exit;
			
			//分割地址
			foreach ($outOrderBatch as $k=>$v){
			    $v['label'] = 'asdfas';

			    if(!$v['label']){return '请完善团购`网站代码`';}
				if(!$v['p_id']){return '缺少g_id or p_id';}
				if(!$v['g_name']){return '缺少g_name';}
				
				
				//调用分割地区插件
				/*$v['label'] = ucfirst(strtolower($v['label']));
				$plug = 'areaPlugFor'.$v['label'];//相应的插件名称
				$divArea = $this -> $plug($v['addr']);*/
				$divArea = $this -> areaPlug($v['addr']);//统一用一个吧
				
				//整理一下详细地址
				$p = 1;
				foreach ($divArea as $kkk=>$vvv){
					$tmp[$p]['ids'] = $kkk;
					if($vvv==''){
						$tmp[$p]['area'] = '未匹配到_';
					}else{
						$tmp[$p]['area'] = $vvv;
					}
					$p++;
				}unset($divArea);
				
				$outOrderBatch[$k]['divarea'] = $tmp;
				
				//物流公司名称
				$outOrderBatch[$k]['logistic_name'] = $logistic[$outOrderBatch[$k]['logistics_com']];
				if($outOrderBatch[$k]['logistics_no'] == ''){
					$outOrderBatch[$k]['logistics_no'] = '888';
				}
			}
			
			
			//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/rss.txt', print_r($outOrderBatch,1)); exit;
			$stock = new Admin_Models_API_Stock();//还要减去 对应货物批次的 real_out_number

            $order_api = new Admin_Models_API_Order();
			
			$batchDetail = $this -> getBatch(array('batch'=>$batch));
			$batchDetail = array_shift($batchDetail['datas']);
			$batchDetail['stock_batch_info_'] = unserialize($batchDetail['stock_batch_info']);
			
			/**
			 * 数据准备就绪，开始入库
			 * 
			 * 插入表：
			 * shop_order
			 * shop_order_batch
			 * shop_order_batch _goods
			 * shop_order_pay_log        这张表也不用了
			 * shop_outstock
			 * shop_outstock_detail
			 * shop_transport
			 * shop_transport_track
			 * shop_order_batch_adjust   调整金额，用于计算小数出错
			 * shop_report_product_sale  2012.6.27插入一张表。用于->运营管理.产品销售统计.产品销售排行
			 * 
			 * 运行过程中可能会出现的错误：1：mysql执行错误 ； 2：php执行超时
			 */
			$cat_ids = array();//保存系统分类id
			foreach($outOrderBatch as $kk=>$vv){
				$op = ($this -> _auth['admin_name'])?($this -> _auth['admin_name']):'系统计划任务';
				
				//取得cat_id
				if(!isset($cat_ids[$vv['p_id']])){
					$temp = $this -> _db -> getProduct(array('product_id'=>$vv['p_id']), 't1.cat_id');
					$cat_ids[$vv['p_id']] = $temp[0]['cat_id'];
				}
				
				
				//
				$SN = $this -> genSN();

				//
				$isNO = (is_numeric($vv['order_time']))?1:0;
				$vv['ctime'] = $isNO?$vv['order_time']:$vv['ctime'];
				$vv['logistics_time'] = ($vv['logistics_time'])?$vv['logistics_time']:$vv['ctime'];
				//插入shop_order表
				$row = array(
					'order_sn'=>$SN,
					'batch_sn'=>$SN,
					'user_id'=>$this -> outOrderUserID,
					'user_name'=>$this -> outOrderUser,
					'shop_id'=>$vv['shop_id'],
					'external_order_sn'=>$vv['order_sn'],
					'add_time'=>$vv['ctime']
				);
				$order_id = $this -> insertIntoOrder($row);
				
				//插入shop_order_batch表
				$row = array(
					'order_id'=>$order_id,
					'order_sn'=>$SN,
					'batch_sn'=>$SN,
					'type'=>13,// 13 为渠道下单
					'is_send'=>($vv['ischeat'])?0:1,
					'note_print'=>$vv['remark'],
					'status'=>($vv['ischeat'])?3:0,
					'status_logistic'=>($vv['ischeat'])?2:4,
					'status_pay'=>2,
					'price_order'=>$vv['supply_price'],
					'price_goods'=>$vv['supply_price'],
					'price_pay'=>$vv['supply_price'],
					'price_payed'=>$vv['supply_price'],
					'balance_amount'=>$vv['supply_price'],
					'price_before_return'=>$vv['supply_price'],
					'pay_type'=>'external',
					'pay_name'=>'渠道支付',
					'pay_time' => $vv['ctime'],
					'logistic_name'=>$vv['logistic_name'],
					'logistic_code'=>$vv['logistics_com'],
					'logistic_no'=>$vv['logistics_no'],
					'logistic_time'=>$vv['logistics_time'],
					'addr_consignee'=>$vv['name'],
					'addr_province'=>($vv['divarea'][1]['area'])?$vv['divarea'][1]['area']:'未知',
					'addr_city'=>($vv['divarea'][2]['area'])?$vv['divarea'][2]['area']:'未知',
					'addr_area'=>($vv['divarea'][3]['area'])?$vv['divarea'][3]['area']:'未知',
					'addr_province_id'=>$vv['divarea'][1]['ids'],
					'addr_city_id'=>$vv['divarea'][2]['ids'],
					'addr_area_id'=>$vv['divarea'][3]['ids'],
					'addr_address'=>($vv['divarea'][4]['area'])?$vv['divarea'][4]['area']:'未知',
					'addr_zip'=>$vv['postcode'],
					'addr_mobile'=>$vv['mobile'],
					'add_time'=>$vv['ctime']
				);
				$order_batch_id = $this -> insertIntoOrderBatch($row);
				
				//插入shop_order_batch_goods表
				//情况1：$vv['order_goods_cfg']存在     是否为（任意数量商品（多个）比如拉手A商品n个，B商品n个）
				if($vv['order_goods_cfg']){
					$subgoods = unserialize($vv['order_goods_cfg']);
					foreach ($subgoods as $var){
						$temp = $this -> _db -> getProduct(array('product_id'=>$var['p_id']), 't1.product_sn,t1.product_name,t1.cat_id,t1.goods_style,t2.goods_id');
						$temp = array_shift($temp);
						//
						$shop_out_tuan_goods = $this -> getOutTuanGoods(array('goods_id'=>$vv['goods_id'], 'quan'=>1));
						$shop_out_tuan_goods = array_shift($shop_out_tuan_goods['datas']);
						//
						$row = array(
							'order_id'=>$order_id,
							'order_batch_id'=>$order_batch_id,
							'order_sn'=>$SN,
							'batch_sn'=>$SN,
							'is_send'=>($vv['ischeat'])?0:1,
							'product_id'=>$var['p_id'],
							'product_sn'=>$temp['product_sn'],
							'goods_id'=>$temp['goods_id'],
							'goods_name'=>$temp['product_name'],
							'cat_id'=>$temp['cat_id'],
							'goods_style'=>$temp['goods_style'],
							'price'=>$shop_out_tuan_goods['supply_price'],
							'sale_price'=>$shop_out_tuan_goods['supply_price'],
							'eq_price'=>$shop_out_tuan_goods['supply_price'],
							'number'=>$var['amt'],
							'add_time'=>$vv['ctime']
						);
						$order_batch_goods_id = $this -> insertIntoOrderBatchGoods($row);
						if(!$vv['ischeat']){
							//real_out_number
							if($batchDetail['stock_batch_info_'][$var['p_id']])foreach ($batchDetail['stock_batch_info_'][$var['p_id']] as $a=>$b){
								$w = array(
									'lid'=>isset($b['lid']) ? $b['lid'] : 1, 
									'batch_id'=>$a, 
									'product_id'=>$var['p_id'], 
									'status_id'=>isset($b['status_id']) ? $b['status_id'] : 2
								);
								$stock -> addStockRealOutNumber($var['amt'], $w, $SN);
								break;
							}
						}
					}
				}
				//情况2：$vv['order_goods_cfg']不存在    正常商品or组合商品；
				else{
					/*先判断goods_sn长度*/
					if(strlen($vv['goods_sn']) == 9){//组合商品
						//取出这个组合商品
						$groupGoods = $this -> _db -> getGroupGoodsBySN($vv['goods_sn']);
						//插入总商品
						$row = array(
							'order_id'=>$order_id,
							'order_batch_id'=>$order_batch_id,
							'order_sn'=>$SN,
							'batch_sn'=>$SN,
							'type'=>5,
							'is_send'=>($vv['ischeat'])?0:1,
							'product_id'=>0,
							'product_sn'=>$vv['goods_sn'],
							'goods_id'=>0,
							'goods_name'=>$groupGoods['group_goods_name'],
							'goods_style'=>$groupGoods['group_specification'],
							'price'=>$vv['supply_price']/($vv['goods_number']*$vv['amount']),
							'sale_price'=>$vv['supply_price']/($vv['goods_number']*$vv['amount']),
							'eq_price'=>$vv['supply_price']/($vv['goods_number']*$vv['amount']),
							'number'=>$vv['goods_number']*$vv['amount'],
							'add_time'=>$vv['ctime']
						);
						$order_batch_goods_id = $this -> insertIntoOrderBatchGoods($row);
						//取出这个组合商品config
						$configs = null;
						$config = null;
						$configs = unserialize($groupGoods['group_goods_config']);
						foreach ($configs as $ka=>$va){
							$tmp = $this -> _db -> getProduct(array('product_id'=>$va['product_id']),'t2.goods_id,t1.product_id,t1.product_sn,t2.goods_sn,t1.product_name as goods_name,t1.goods_style,t2.price,t1.cat_id');
							$tmp[0]['number'] = $va['number'];
							$config[] = $tmp[0]; 
						}
						//先找出 子商品分别有多少，便于统计eq_price
	        			$subGoodsTotalPrice = 0;
						foreach ($config as $ks=>$vs){
							$subGoodsTotalPrice += $vs['number']*$vv['amount']*$vs['price'];
						}
						//插入子商品
						foreach ($config as $kb=>$vb){
							$row = array(
								'order_id'=>$order_id,
								'order_batch_id'=>$order_batch_id,
								'order_sn'=>$SN,
								'batch_sn'=>$SN,
								'parent_id'=>$order_batch_goods_id,
								'type'=>5,
								'is_send'=>($vv['ischeat'])?0:1,
								'product_id'=>$vb['product_id'],
								'product_sn'=>$vb['product_sn'],
								'goods_id'=>($vb['goods_id'])?$vb['goods_id']:0,
								'goods_name'=>$vb['goods_name'],
								'cat_id'=>$vb['cat_id'],
								'goods_style'=>$vb['goods_style'],
								'number'=>$vb['number']*$vv['amount'],
								'price'=>($vb['price'])?$vb['price']:0,
								'sale_price'=>@(($vv['supply_price']*$vv['amount']*$vb['price'])/$subGoodsTotalPrice),
								'eq_price'=>@(($vv['supply_price']*$vv['amount']*$vb['price'])/$subGoodsTotalPrice),
								'add_time'=>$vv['ctime']
							);
							$this -> insertIntoOrderBatchGoods($row);
							if(!$vv['ischeat']){
								//real_out_number
								if($batchDetail['stock_batch_info_'][$vb['product_id']])foreach ($batchDetail['stock_batch_info_'][$vb['product_id']] as $a=>$b){
									$w = array(
										'lid'=>isset($b['lid']) ? $b['lid'] : 1, 
										'batch_id'=>$a, 
										'product_id'=>$vb['product_id'], 
										'status_id'=>isset($b['status_id']) ? $b['status_id'] : 2
									);
									$stock -> addStockRealOutNumber($vb['number']*$vv['amount'], $w, $SN);
									break;
								}
							}
						}
					}else{//正常商品
						$row = array(
							'order_id'=>$order_id,
							'order_batch_id'=>$order_batch_id,
							'order_sn'=>$SN,
							'batch_sn'=>$SN,
							'is_send'=>($vv['ischeat'])?0:1,
							'product_id'=>$vv['p_id'],
							'product_sn'=>$vv['goods_sn'],
							'goods_id'=>$vv['g_id'],
							'goods_name'=>$vv['g_name'],
							'cat_id'=>$cat_ids[$vv['p_id']],
							'goods_style'=>$vv['g_style'],
							'price'=>$vv['supply_price']/($vv['goods_number']*$vv['amount']),
							'sale_price'=>$vv['supply_price']/($vv['goods_number']*$vv['amount']),
							'eq_price'=>$vv['supply_price']/($vv['goods_number']*$vv['amount']),
							'number'=>$vv['goods_number']*$vv['amount'],
							'add_time'=>$vv['ctime']
						);
						$order_batch_goods_id = $this -> insertIntoOrderBatchGoods($row);
						if(!$vv['ischeat']){
							//real_out_number
							if($batchDetail['stock_batch_info_'][$vv['p_id']])
								foreach ($batchDetail['stock_batch_info_'][$vv['p_id']] as $a=>$b){
									$w = array(
										'lid'=>isset($b['lid']) ? $b['lid'] : 1, 
										'batch_id'=>$a,
										'product_id'=>$vv['p_id'], 
										'status_id'=>isset($b['status_id']) ? $b['status_id'] : 2
									);
									$stock -> addStockRealOutNumber($vv['goods_number']*$vv['amount'], $w, $SN);
									break;
								}
						}
					}
				}
				
				//判断均价是否等于订单金额，如果不等于则插入调整金额，shop_order_batch_adjust
				$p_eq = round($vv['supply_price']/($vv['goods_number']*$vv['amount']),2);//保留两位，会导致小数算错
				$p_adjust = $vv['supply_price']-$p_eq*($vv['goods_number']*$vv['amount']);//调整金额
				if($p_adjust != 0){
					$row = array(
						'order_sn' => $SN,
						'batch_sn' => $SN,
						'money' => $p_adjust,
						'add_time'=>$vv['ctime']
					);
					$order_batch_adjust = $this -> insertIntoOrderBatchAdjust($row);
				}
				
				//添加应收款记录
				if (!$this -> financeAPI) {
				    $this -> financeAPI = new Admin_Models_API_Finance();
				}
                $receiveData = array('batch_sn' => $SN,
                                     'type' => 3,
                                     'pay_type' => 'external',
                                     'amount' => $vv['supply_price'],
                                    );
                $this -> financeAPI -> addFinanceReceivable($receiveData);
				
				//如果是刷单 则 continue
				if($vv['ischeat']){ continue; }
				
				//插入shop_order_pay_log表
				/*$time = time();
				$row = array(
					'pay_log_id'=>$SN.'-'.$time,
					'batch_sn'=>$SN,
					'add_time'=>$time,
					'pay_type'=>'service',
					'pay'=>$vv['supply_price']
				);
				$this -> _db -> insertIntoOrderPayLog($row);*/
				
				/*这两条记录已经在检查库存过程中插入了，此时只要更新一下bill_no就行，（shop_id:external_order_sn 更新成 order_sn）
				 * 
				//插入shop_outstock表
				$row = array(
					'lid'=>2,
					'item_no'=>'',
					'bill_no'=>$SN,
					'bill_type'=>1,
					'bill_status'=>5,
					'admin_name'=>$op,
					'add_time'=>$vv['ctime']
				);
				$outstock_id = $this -> insertIntoOutstock($row);
				
				//插入 shop_outstock_detail 表
				$row = array(
					'outstock_id'=>$outstock_id,
					'product_id'=>$vv['p_id'],
					'status_id'=>2,
					'number'=>($vv['goods_number']*$vv['amount']),
					'shop_price'=>$vv['supply_price']
				);
				$this -> insertIntoOutstockDetail($row);
				*
				*/
				
				//更新 shop_outstock.bill_no
				$this -> _db -> updateTable('shop_outstock', array('bill_no'=>$SN, 'bill_status'=>5, 'finish_time'=>time()), "bill_no='".$vv['shop_id'].':'.$vv['order_sn']."'");
				
				
				//插入 shop_transport 表
				$row = array(
					'bill_no'=>$SN,
					'consignee'=>$vv['name'],
					'province'=>($vv['divarea'][1]['area'])?$vv['divarea'][1]['area']:'未知',
					'city'=>($vv['divarea'][2]['area'])?$vv['divarea'][2]['area']:'未知',
					'area'=>($vv['divarea'][3]['area'])?$vv['divarea'][3]['area']:'未知',
					'province_id'=>$vv['divarea'][1]['ids'],
					'city_id'=>$vv['divarea'][2]['ids'],
					'area_id'=>$vv['divarea'][3]['ids'],
					'address'=>($vv['divarea'][4]['area'])?$vv['divarea'][4]['area']:'未知',
					'zip'=>$vv['postcode'],
					'mobile'=>$vv['mobile'],
					'number'=>$vv['amount'],
					'goods_number'=>$vv['goods_number']*$vv['amount'],
					'amount'=>$vv['supply_price'],
					'print_remark'=>$vv['remark'],
					'remark'=>$vv['remark'],
					'logistic_name'=>$vv['logistic_name'],
					'logistic_code'=>$vv['logistics_com'],
					'logistic_no'=>$vv['logistics_no'],
					'search_mod'=>'排除法',
					'is_assign'=>1,
					'is_confirm'=>1,
					'is_track'=>1,
					'logistic_status'=>2,
					'admin_name'=>$op,
					'send_time'=>$vv['logistics_time'],
					'add_time'=>$vv['ctime'],
					'shop_id'=>$vv['shop_id']
				);
				$this -> insertIntoTransport($row);
				
				//插入 shop_transport_track 表
				$row = array(
					'item_no'=>$SN,
					'logistic_no'=>$vv['logistics_no'],
					'logistic_code'=>$vv['logistics_com'],
					'logistic_status'=>2,
					'admin_name'=>$op,
					'remark'=>'外部团购订单导入',
					'op_time'=>$vv['ctime']
				);
				$this -> insertIntoTransportTrack($row);
				
				//插入shop_report_product_sale 2012.6.27添加
				$row = array(
					'g_id'=>$vv['g_id'],
					'p_id'=>$vv['p_id'],
					'ctime'=>$vv['ctime'],
					'sale_money'=>$vv['supply_price'],
					'number'=>($vv['goods_number']*$vv['amount'])
				);

                //
				$order_api -> orderDetail($SN);
			}
			
			//更新 shop_out_tuan_batch 表状态
			$this -> batchUpdate(array('tongbu'=>1, 'tongbu_time'=>time(), 'tongbu_admin'=>$op), " batch=".$batch);
			
			return 'ok';
		}else{
			$rs = $this -> _db -> batchDel($batch);
			return $rs;
		}
	}
	
	/**
	 * 中文字符串截取
	 */
	public function _msubstr($str, $start=0, $length, $charset="utf-8", $suffix=false){
	    if(function_exists("mb_substr"))
	        return mb_substr($str, $start, $length, $charset);
	    elseif(function_exists('iconv_substr')) {
	        return iconv_substr($str,$start,$length,$charset);
	    }
	    $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	    preg_match_all($re[$charset], $str, $match);
	    $slice = join("",array_slice($match[0], $start, $length));
	    if($suffix) return $slice."…";
	    return $slice;
	}
	
	/**
	 * 地址匹配
	 * 
	 * @param string $base  完整地址，包含省、市、区、县、详细地址
	 * 
	 * @return array
	 */
	public function areaPlug($base) {
		$pos = 0;
		$base = str_replace(' ', '', str_replace(',', '', str_replace('，', '', $base)));
		$sCity = array('北京','上海','天津','重庆');//直辖市
		
		//特殊情况（直辖市）
		$special = $this -> _msubstr($base, 0, 2);
		if(in_array($special, $sCity)){
			$rs = $this -> _db -> isAreaInDB($special);
			$divArea[$rs] = $this -> _msubstr($base, 0, 2);//省
			if($special == '北京'){$pid = 33; $divArea[$pid] = '北京市';}
			elseif($special == '上海'){$pid = 105; $divArea[$pid] = '上海市';}
			elseif($special == '天津'){$pid = 34; $divArea[$pid] = '天津市';}
			elseif($special == '重庆'){$pid = 267; $divArea[$pid] = '重庆市';}
			else{exit('参数错误');}
			//取出特殊城市下的区
			$subArea = $this -> _db -> getSubArea($pid);
			$sign = false;
			foreach ($subArea as $kk=>$vv){
				$sign  = strpos($base, $vv['area_name']);
				if($sign!==false){
					$divArea[$vv['area_id']] = $vv['area_name'];
					break;
				}
			}
			if($sign == false){$divArea[$subArea[0]['area_id']] = $subArea[0]['area_name'];}//如果还是没有匹配到，则默认匹配第一个
			$divArea[] = $base;
			return $divArea;
		}
		
		//正常情况下 
		//省份
		for($j=2;$j<5;$j++){
			$rs = $this -> _db -> isAreaInDB($this -> _msubstr($base, 0, $j), 1);
			if($rs){
				//判断下个字是否为“省”字
				if($this -> _msubstr($base, $j, 1)=="省"){$pos = $j+1;}//$j+1 是为了截取"省"字
				else{$pos = $j;}
				$str = $this -> _msubstr($base, 0, $pos);
				break;
			}else{
				$str='';
				$pos=0;
			}
		}
		if($pos == 0){//如果没匹配到省，则赋“未知”值，返回
			$divArea[3982] = '未知省';
			$divArea[3983] = '未知市';
			$divArea[3984] = '未知区';
			$divArea[] = $base;
			return $divArea;
		}
		if($str==''){$rs = 1;}
		$divArea[$rs] = $str;//保存
		
		$base = $this -> _msubstr($base, $pos, strlen($base));//去掉省份
		
		//城市
		for($j=3;$j<12;$j++){//城市,特区,州，2-11长度
			$rs = $this -> _db -> isAreaInDB($this -> _msubstr($base, 0, $j));
			if($rs){
				$str=$this -> _msubstr($base, 0, $j);
				$pos=$j;
				break;
			}else{
				$str='';
				$pos=0;
			}
		}
		if($pos == 0){
			$divArea[3983] = '未知市';//保存
			$idForNext = $rs;
		}else{
			$divArea[$rs] = $str;//保存
			$idForNext = $rs;
		}
		$base = $this -> _msubstr($base, $pos, strlen($base));//去掉城市
		
		//区镇县旗
		for($j=2;$j<16;$j++){//镇区县旗，2-15长度
			$rs = $this -> _db -> isAreaInDB($this -> _msubstr($base, 0, $j));
			if($rs){
				$str=$this -> _msubstr($base, 0, $j);
				$pos=$j;
				break;
			}else{
				$str='';
				$pos=0;
			}
		}
		if($pos == 0){
			$divArea[3984] = '未知区';//保存
		}else{
			$divArea[$rs] = $str;//保存
			$base = $this -> _msubstr($base, $pos, strlen($base));//去掉镇区县旗
		}
		
		//详细地址
		$divArea[] = $base;
		
		return $divArea;
	}
	
	/**
	 * 点评团      地址匹配
	 * 
	 * @param string $base  完整地址，包含省、市、区、县、详细地址
	 * 
	 * @return array
	 */
	public function areaPlugForDianping($base) {
		return $this -> areaPlug($base);
	}
	
	/**
	 * 拉手      地址匹配
	 * 
	 * @param string $base  完整地址，包含省、市、区、县、详细地址
	 * 
	 * @return array
	 */
	public function areaPlugForLashou($base) {
		return $this -> areaPlug($base);
	}
	
	/**
	 * 美团      插地址匹
	 * 
	 * @param string $base  完整地址，包含省、市、区、县、详细地址
	 * 
	 * @return array
	 */
	public function areaPlugForMeituan($base) {
		return $this -> areaPlug($base);
	}
	
	/**
	 * 好特会      地址匹配
	 * 
	 * @param string $base  完整地址，包含省、市、区、县、详细地址
	 * 
	 * @return array
	 */
	public function areaPlugForHaotehui($base) {
		return $this -> areaPlug($base);
	}
	
	/**
	 * 高鹏      地址匹配
	 * 
	 * @param string $base  完整地址，包含省、市、区、县、详细地址
	 * 
	 * @return array
	 */
	public function areaPlugForGaopeng($base) {
		$base = str_replace(',', '', str_replace('，', '', $base));
		return $this -> areaPlug($base);
	}
	
	/**
	 * 24券      地址匹配
	 * 
	 * @param string $base  完整地址，包含省、市、区、县、详细地址
	 * 
	 * @return array
	 */
	public function areaPlugForQuan($base) {
		$base = str_replace(',', '', str_replace('，', '', $base));
		return $this -> areaPlug($base);
	}
	
	/**
	 * QQ     地址匹配
	 * 
	 * @param string $base  完整地址，包含省、市、区、县、详细地址
	 * 
	 * @return array
	 */
	public function areaPlugForQq($base) {
		$base = str_replace(',', '', str_replace('，', '', $base));
		return $this -> areaPlug($base);
	}
	
	/**
	 * 工商银行     地址匹配
	 * 
	 * @param string $base  完整地址，包含省、市、区、县、详细地址
	 * 
	 * @return array
	 */
	public function areaPlugForIcbc($base) {
		$base = str_replace(',', '', str_replace('，', '', $base));
		return $this -> areaPlug($base);
	}
	
	/*外部订单入库接口*/
	
	/**
	 * 插入 shop_order
	 * 
	 * @param array $arr
	 * 
	 * @return int $insertID
	 */
	public function insertIntoOrder($arr) {
		$row = array(
			'order_sn'=>$arr['order_sn'],
			'batch_sn'=>$arr['order_sn'],
			'add_time'=>$arr['add_time'],
			'user_id'=>$arr['user_id'],
			'user_name'=>$arr['user_name'],
			'rank_id'=>$arr['rank_id']?$arr['rank_id']:1,
			'invoice'=>$arr['invoice']?$arr['invoice']:'',
			'invoice_content'=>$arr['invoice_content']?$arr['invoice_content']:'',
			'shop_id'=>$arr['shop_id']?$arr['shop_id']:0,
			'external_order_sn'=>$arr['external_order_sn']?$arr['external_order_sn']:''
		);
		return $this -> _db -> insertIntoOrder($row);
	}
	
	/**
	 * 插入 shop_order_batch
	 * 
	 * @param array $arr
	 * 
	 * @return int $insertID
	 */
	public function insertIntoOrderBatch($arr) {
		$row = array(
			'order_id'=>$arr['order_id'],
			'order_sn'=>$arr['order_sn'],
			'batch_sn'=>$arr['batch_sn'],
			'type'=>$arr['type']?$arr['type']:0,
			'add_time'=>$arr['add_time'],
			'is_visit'=>0,
			'is_send'=>$arr['is_send']?$arr['is_send']:0,
			'note_print'=>$arr['note_print']?$arr['note_print']:'',
			'status'=>$arr['status']?$arr['status']:0,
			'status_logistic'=>$arr['status_logistic']?$arr['status_logistic']:0,
			'status_pay'=>$arr['status_pay']?$arr['status_pay']:0,
			'price_order'=>$arr['price_order'],
			'price_goods'=>$arr['price_goods'],
			'price_logistic'=>$arr['price_logistic']?$arr['price_logistic']:0,
			'price_adjust'=>$arr['price_adjust']?$arr['price_adjust']:'NULL',
			'price_pay'=>$arr['price_pay'],
			'price_payed'=>$arr['price_payed'],
			'balance_amount'=>$arr['price_order'],
			'price_before_return'=>$arr['price_before_return'],
			'pay_type'=>$arr['pay_type']?$arr['pay_type']:'external',
			'pay_name'=>$arr['pay_name']?$arr['pay_name']:'渠道支付',
			'logistic_name'=>$arr['logistic_name'],
			'logistic_code'=>$arr['logistic_code'],
			'logistic_price'=>$arr['logistic_price']?$arr['logistic_price']:0,
			'logistic_price_cod'=>$arr['logistic_price_cod']?$arr['logistic_price_cod']:0,
			'logistic_fee_service'=>$arr['logistic_fee_service']?$arr['logistic_fee_service']:0,
			'logistic_list'=>$arr['logistic_list']?$arr['logistic_list']:'',
			'logistic_no'=>$arr['logistic_no']?$arr['logistic_no']:'',
			'logistic_time'=>$arr['logistic_time']?$arr['logistic_time']:'',
			'addr_consignee'=>$arr['addr_consignee'],
			'addr_province'=>$arr['addr_province'],
			'addr_city'=>$arr['addr_city'],
			'addr_area'=>$arr['addr_area'],
			'addr_province_id'=>$arr['addr_province_id'],
			'addr_city_id'=>$arr['addr_city_id'],
			'addr_area_id'=>$arr['addr_area_id'],
			'addr_address'=>$arr['addr_address'],
			'addr_zip'=>$arr['addr_zip'],
			'addr_tel'=>$arr['addr_tel'],
			'addr_mobile'=>$arr['addr_mobile']
		);
		return $this -> _db -> insertIntoOrderBatch($row);
	}
	
	/**
	 * 插入shop_order_batch_goods表
	 * 
	 * @param array $arr
	 * 
	 * @return int $insertID
	 */
	public function insertIntoOrderBatchGoods($arr) {
		$row = array(
			'order_id'=>$arr['order_id'],
			'order_batch_id'=>$arr['order_batch_id'],
			'order_sn'=>$arr['order_sn'],
			'batch_sn'=>$arr['batch_sn'],
			'parent_id'=>$arr['parent_id']?$arr['parent_id']:0,
			'type'=>$arr['type']?$arr['type']:0,
			'is_send'=>$arr['is_send']?$arr['is_send']:0,
			'add_time'=>$arr['add_time'],
			'product_id'=>$arr['product_id'],
			'product_sn'=>$arr['product_sn'],
			'goods_id'=>$arr['goods_id'],
			'goods_name'=>$arr['goods_name']?$arr['goods_name']:'',
			'cat_id'=>$arr['cat_id']?$arr['cat_id']:0,
			'cat_name'=>$arr['cat_name']?$arr['cat_name']:'',
			'weight'=>$arr['weight']?$arr['weight']:0,
			'length'=>$arr['length']?$arr['length']:0,
			'width'=>$arr['width']?$arr['width']:0,
			'height'=>$arr['height']?$arr['height']:0,
			'goods_style'=>$arr['goods_style'],
			'cost'=>$arr['cost']?$arr['cost']:0,
			'price'=>$arr['price']?$arr['price']:0,
			'sale_price'=>$arr['sale_price'],
			'eq_price'=>$arr['eq_price'],
			'number'=>$arr['number'],
			'return_number'=>$arr['return_number']?$arr['return_number']:0,
			'returning_number'=>$arr['returning_number']?$arr['returning_number']:0
		);
		return $this -> _db -> insertIntoOrderBatchGoods($row);
	}
	
	/**
	 * 插入 shop_outstock 表
	 * 
	 * @param array $arr
	 * 
	 * @return int $insertID
	 */
	public function insertIntoOutstock($arr) {
		$row = array(
			'lid'=>$arr['lid']?$arr['lid']:2,
			'item_no'=>$arr['item_no']?$arr['item_no']:'',
			'bill_no'=>$arr['bill_no'],
			'bill_type'=>$arr['bill_type']?$arr['bill_type']:0,
			'bill_status'=>$arr['bill_status']?$arr['bill_status']:0,
			'supplier_id'=>$arr['supplier_id']?$arr['supplier_id']:0,
			'is_back'=>$arr['is_back']?$arr['is_back']:0,
			'is_cancel'=>$arr['is_cancel']?$arr['is_cancel']:0,
			'add_time'=>$arr['add_time'],
			'admin_name'=>$arr['admin_name']?$arr['admin_name']:'',
			'lock_name'=>''
		);
		return $this -> _db -> insertIntoOutstock($row);
	}
	
	/**
	 * 插入 shop_outstock_detail 表
	 * 
	 * @param array $arr
	 * 
	 * @return int $insertID
	 */
	public function insertIntoOutstockDetail($arr) {
		$row = array(
			'outstock_id'=>$arr['outstock_id'],
			'product_id'=>$arr['product_id'],
			'status_id'=>$arr['status_id']?$arr['status_id']:0,
			'number'=>$arr['number']?$arr['number']:0,
			'shop_price'=>$arr['shop_price']?$arr['shop_price']:0
		);
		return $this -> _db -> insertIntoOutstockDetail($row);
	}
	
	/**
	 * 插入 transport 表
	 * 
	 * @param array $arr
	 * 
	 * @return int $insertID
	 */
	public function insertIntoTransport($arr) {
		$row = array(
			'item_no'=>$arr['item_no']?$arr['item_no']:0,
			'bill_type'=>$arr['bill_type']?$arr['bill_type']:1,
			'bill_no'=>$arr['bill_no'],
			'consignee'=>$arr['consignee'],
			'province'=>$arr['province'],
			'city'=>$arr['city'],
			'area'=>$arr['area'],
			'province_id'=>$arr['province_id'],
			'city_id'=>$arr['city_id'],
			'area_id'=>$arr['area_id'],
			'address'=>$arr['address'],
			'zip'=>$arr['zip'],
			'mobile'=>$arr['mobile'],
			'number'=>$arr['number'],
			'goods_number'=>$arr['goods_number'],
			'amount'=>$arr['amount'],
			'weight'=>$arr['weight']?$arr['weight']:1,
			'volume'=>$arr['volume']?$arr['volume']:1,
			'print_remark'=>$arr['print_remark'],
			'remark'=>$arr['remark'],
			'logistic_name'=>$arr['logistic_name'],
			'logistic_code'=>$arr['logistic_code'],
			'logistic_no'=>$arr['logistic_no'],
			'search_mod'=>$arr['search_mod']?$arr['search_mod']:'排除法',
			'send_time'=>$arr['send_time'],
			'is_cancel'=>$arr['is_cancel']?$arr['is_cancel']:0,
			'is_assign'=>$arr['is_assign']?$arr['is_assign']:0,
			'is_confirm'=>$arr['is_confirm']?$arr['is_confirm']:0,
			'is_track'=>$arr['is_track']?$arr['is_track']:0,
			'add_time'=>$arr['add_time'],
			'logistic_status'=>$arr['logistic_status']?$arr['logistic_status']:0,
			'admin_name'=>$arr['admin_name']?$arr['admin_name']:'',
			'lock_name'=>$arr['lock_name']?$arr['lock_name']:'',
			'shop_id'=>$arr['shop_id']?$arr['shop_id']:11
		);
		return $this -> _db -> insertIntoTransport($row);
	}
	
	/**
	 * 插入 shop_transport_track 表
	 * 
	 * @param array $arr
	 * 
	 * @return int $insertID
	 */
	public function insertIntoTransportTrack($arr) {
		$row = array(
			'item_no'=>$arr['item_no'],
			'logistic_no'=>$arr['logistic_no'],
			'logistic_code'=>$arr['logistic_code'],
			'logistic_status'=>$arr['logistic_status']?$arr['logistic_status']:0,
			'op_time'=>$arr['op_time'],
			'admin_name'=>$arr['admin_name']?$arr['admin_name']:'',
			'remark'=>$arr['remark']?$arr['remark']:''
		);
		return $this -> _db -> insertIntoTransportTrack($row);
	}
	
	/**
	 * 插入 shop_batch_adjust 表
	 * 
	 * @param array $arr
	 * 
	 * @return int $insertID
	 */
	public function insertIntoOrderBatchAdjust($arr) {
		$row = array(
			'order_sn' => $arr['order_sn'],
			'batch_sn' => $arr['batch_sn'],
			'type' => 1,
			'money' => $arr['money'],
			'note' => '外部团购订单小数点误差调整',
			'add_time' => $arr['add_time']
		);
		return $this -> _db -> insertIntoOrderBatchAdjust($row);
	}
	
	
	/* End::外部团购订单同步到官网订单 */
	
	/**
	 * 修正以前为0金额的订单
	 * 
	 * @param int $batch
	 */
	public function modifyOrderPriceEqZero($batch) {
		$orders = $this -> getOutTuanOrder(array('batch'=>$batch),1,1,null,true);
		if($orders['tot']<1){ return '无此批次'; }
		//
		foreach ($orders['datas'] as $v){
			$goods[$v['goods_id']] = 1;
		}
		//从shop_out_tuan_goods表中取出对应的“销售价格+供货价格”
		foreach ($goods as $k=>$v){
			$rs = $this -> getOutTuanGoods(array('goods_id'=>$k,'quan'=>1));
			$goods[$k] = $rs['datas'][0];
		}
		//开始更新价格
		foreach ($orders['datas'] as $k=>$v){
			$set = array(
				'order_price'=>$goods[$v['goods_id']]['goods_price']*$v['amount'],
				'supply_price'=>$goods[$v['goods_id']]['supply_price']*$v['amount'],
				'fee'=>($goods[$v['goods_id']]['goods_price']-$goods[$v['goods_id']]['supply_price'])*$v['amount']
			);
			$this -> orderEdit($set, ' id='.$v['id']);
		}
		//dump($goods,1,1);
		return 'ok';
	}
	
	/**
	 * 重置批次同步状态 为 0
	 * 
	 * @param int $batch
	 * @param int $s
	 */
	public function tongbuReset($batch, $s) {
		$this -> batchUpdate(array('tongbu'=>$s), " batch=".$batch);
		return 'ok';
	}
	
	/**
	 * 单个删除同步订单
	 * 
	 * @param string $sn
	 */
	public function deleteTongbuOrder($sn) {
		return $this -> _db -> deleteTongbuOrder($sn);
	}
	
	/**
	 * 导出报表
	 * 
	 * @param array $datas
	 */
	public function exportReport($datas) {
		$row1 = array('商品编码','商品名称','销售总金额','销售量','平均售价',);
		$xls = new Custom_Model_GenExcel();
		$xls -> addRow($row1);
		$xls -> addArray($datas);
		$xls -> generateXML ("mytest");
		exit;
	}
	
	/**
	 * 修正时间
	 * 
	 * @param int $batch
	 * 
	 * @return string
	 */
	public function modifyTime($batch) {
		if($batch){
			//取出一个批次的订单
			$orders = $this -> getOutTuanOrder(array('batch'=>$batch),1,1,null,true);
			$order_sn = null;
			foreach ($orders['datas'] as $kk=>$vv){
				$order_sn[] = $vv['order_sn'];
			}
			$order_sn = implode(',', $order_sn);
			$ctime = $orders['datas'][0]['ctime'];
			//
			$wgOrder = $this -> getGWOrder(array('external'=>$order_sn));
			if($wgOrder){
				foreach ($wgOrder as $kkk=>$vvv){
					$vvv['order_sn'] = trim($vvv['order_sn']);
					if($vvv['order_sn']){
						$this -> _db -> updateGWOrder($vvv['order_sn'], $ctime);
					}
				}
			}else{
				return '没有找到订单';
			}
			//
			return 'ok';
		}
		return '没有批次号';
	}
	
	/**
	 * 得到官网订单 
	 * 
	 */
	public function getGWOrder($arr=null, $tj=null) {
		$where = null;
		if($arr){
			$where = ' where 1=1';
			if($tj){
				$arr['external'] && $where .= " and external_order_sn = '".$arr['external']."'";
			}else{
				$arr['external'] && $where .= " and external_order_sn in (".$arr['external'].")";
			}
		}
		return $this -> _db -> getGWOrder('order_sn',$where);
	}
	
	/**
	 * 修正 表
	 * shop_order_batch_goods字段：product_id、goods_id
	 * shop_outstock_detail字段：product_id
	 * 
	 * 插入表
	 * shop_report_product_sale
	 */
	public function modifyGidPid($batch) {exit;
		if($batch){
			//取出一个批次的订单
			$orders = $this -> getOutTuanOrder(array('batch'=>$batch,'ischeat'=>'off'),1,1,null,1);
			if($orders['tot']<1){return '批次没有订单';}
			//
			foreach ($orders['datas'] as $k=>$v){
				if(strlen($v['goods_sn']) == 9){continue;}
				$wgOrder = $this -> getGWOrder(array('external'=>$v['order_sn']), 1);
				if(is_array($wgOrder) && count($wgOrder)){
					$sn = $wgOrder[0]['order_sn'];
					if($sn && $v['g_id'] && $v['p_id']){
						//修正 goods_id  product_id
						$this -> _db -> modifyGidPid($sn, $v['g_id'], $v['p_id']);
						//插入shop_report_product_sale记录
						$row = array(
							'g_id'=>$v['g_id'],
							'p_id'=>$v['p_id'],
							'ctime'=>$v['ctime'],
							'sale_money'=>$v['supply_price'],
							'number'=>($v['goods_number']*$v['amount'])
						);
						
					}
				}
			}
			//更新shop_out_tuan_batch.xz字段=1
			$this -> batchUpdate(array('xz'=>1), " batch=".$batch);
			return 'ok';
		}
		return '没有批次号';
	}
	
	/**
	 * 按搜索条件导出订单
	 * 
	 * @param array $datas
	 */
	public function exportOrder($datas=null) {
		if($datas){
			header("Pragma:public");
			//物流公司
			$logistic = $this -> getLogistic();
			foreach ($logistic as $index=>$val){
				$tmp[$val['logistic_code']] = $val['name'];
			}
			$logistic = $tmp; unset($tmp);
			//
			$i = 0;
			foreach ($datas['sortdatas'] as $k=>$v){
				if($v)foreach ($v as $kk=>$vv){
					$doc[$i][] = $this -> codeFilter($vv['shop_name']);
					$doc[$i][] = $this -> codeFilter($vv['goods_name']);
					$doc[$i][] = $this -> codeFilter($vv['amount']);
					$doc[$i][] = $this -> codeFilter($vv['order_sn']);
					$doc[$i][] = $this -> codeFilter($vv['batch']);
					$doc[$i][] = $this -> codeFilter($vv['name']);
					$doc[$i][] = $this -> codeFilter($vv['phone']);
					$doc[$i][] = $this -> codeFilter($vv['mobile']);
					$doc[$i][] = $this -> codeFilter($vv['postcode']);
					$doc[$i][] = $this -> codeFilter($vv['addr']);
					$doc[$i][] = $this -> codeFilter($vv['remark']);
					$doc[$i][] = date("Y-m-j H:i",$vv['ctime']);
					$doc[$i][] = $this -> codeFilter($logistic[$vv['logistics_com']]);
					$doc[$i][] = $this -> codeFilter($vv['logistics_no']);
					$i++;
				}
			}
			$doc[$i++] = array('网站', '商品名称', '数量', '订单号', '批次号', '收件人','固定电话', '手机', '邮编', '地址', '备注', '订单导入时间', '快递公司', '快递单号');
			$doc = array_reverse($doc);
			//空行
			$doc[$i++][] = '';
			$doc[$i++][] = '';
			//统计
			foreach ($datas['tj'] as $k=>$v){
				$doc[$i][] = $v['goods_name'];
				$doc[$i][] = $v['ct'];
				$i++;
			}
			//dump($doc,1,1);
			//导出
			$xls = new Custom_Model_GenExcel();
			$xls -> addArray($doc);
			$showname = '按条件导出团购订单';
			if (preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])){
				$showname = urlencode($showname);
			}
			$xls -> generateXML($showname);
			exit();
		}else{
			exit('没有数据');
		}
	}
	
	/**
	 * 添加shop_out_tuan_batch
	 * 
	 * @param array $batch
	 */
	public function addBatch($batch) {
		$this -> _db -> addBatch($batch);
		return 'ok';
	}
	
	/**
	 * 修正 shop_order_batch_goods.cat_id 表
	 * 每次取 5000 条记录来更新
	 * 
	 */
	public function modifyCatID() {
		$cat_ids = array();
		//取得5000条记录
		$batchGoods = $this -> _db -> getOrderBatchGoods('and goods_id!=0 and cat_id=0', 'order_batch_goods_id,goods_id');
		//
		if(is_array($batchGoods) && count($batchGoods)){
			foreach ($batchGoods as $v){
				if(!isset($cat_ids[$v['goods_id']])){
					$temp = $this -> _db -> getGoods(array('goods_id'=>$v['goods_id']));
					if(isset($temp[0]['cat_id'])){
						$cat_ids[$v['goods_id']] = $temp[0]['cat_id'];
					}else{
						$cat_ids[$v['goods_id']] = 0;
					}
				}
			}
			//
			foreach ($batchGoods as $v){
				$set = array('cat_id'=>$cat_ids[$v['goods_id']]);
				$where = " order_batch_goods_id=".$v['order_batch_goods_id']." and goods_id=".$v['goods_id'];
				$this -> _db -> updateOrderBatchGoods($set, $where);
			}
			return '修正'.count($batchGoods).'条记录';
		}else{
			return '修正完成';
		}
	}
	
	/**
	 * 重置批次发货状态
	 * 
	 * @param int $batch
	 * @param int $s
	 */
	public function batchSendReset($batch, $s=0) {
		$this -> batchUpdate(array('logistics'=>$s), " batch=".$batch);
		return 'ok';
	}
	
	/**
	 * 更新批次订单的下单时间
	 * 
	 * @param string $batch
	 * @param int    $ts
	 */
	public function updateOrderTime($batch, $ts) {
		return $this -> _db -> updateOrderTime($batch, $ts);
	}
	
	/**
	 * 验证批次库存
	 * 
	 * @param string $batch
	 */
	public function checkStockDo($batch) {
		$batch = trim($batch); if(!$batch){ return '请输入批次号'; }
		//取出这个批次订单
		$orders = $this -> getOutTuanOrder(array('batch'=>$batch, 'ischeat'=>'off', 'check_stock'=>0), 1, 1, null, true);
		if($orders['tot'] < 1){ return '此批次下没有订单'; }
		$orders = $orders['datas'];
		//
		foreach ($orders as $v){
			if($v['p_id']==0){ return '缺少p_id、g_id'; }
			if($v['amount']==0){ return '没有商品数量'; }
			if(!$v['goods_sn']){ return '没有商品编码'; }
			if(strlen($v['goods_sn'])!=8 && strlen($v['goods_sn'])!=9){ return '商品编码不正确'; }
		}
		//
		//$renew = new Admin_Models_API_Renew();
		$outstock = new Admin_Models_DB_OutStock();
		$outstockApi = new Admin_Models_API_OutStock();
		$stock = new Admin_Models_API_Stock();
		$stock_batch_info = array();
		//
		foreach ($orders as $v){
			$check = array();
			
			//是否为（任意数量商品（多个）比如拉手A商品n个，B商品n个）
			if($v['order_goods_cfg']){
				$subgoods = unserialize($v['order_goods_cfg']);
				$has = true;
				foreach ($subgoods as $var){
					//检查库存
					$rs = $stock -> checkPreSaleProductStock($var['p_id'], $var['amt']);
					if(!$rs){
						$has = false;//如果里面有一个无库存，则不通过
						break;
					}
				}
				if($has){
					
					$outstoc_details = array();//表 shop_outstock_details 记录
					//减库存
					foreach ($subgoods as $vv){
						$num = $vv['amt'];
						$product_id = (int)$vv['p_id'];
						if($product_id<1){ break; }
						
						$productBatchStock = $stock -> createSaleOutStock($product_id, $num, false);
						foreach ($productBatchStock as $ss){
							if(!isset( $stock_batch_info[$product_id][$ss['batch_id']]['number'] )){ $stock_batch_info[$product_id][$ss['batch_id']]['number'] = 0; }
							$stock_batch_info[$product_id][$ss['batch_id']]['number'] += $ss['number'];//记录减去的商品库存批次量
							$stock_batch_info[$product_id][$ss['batch_id']]['lid'] = $ss['lid'];
							$stock_batch_info[$product_id][$ss['batch_id']]['status_id'] = $ss['status_id'];
							//减对应商品批次的库存
							$w = array('lid'=>$ss['lid'], 'batch_id'=>$ss['batch_id'], 'product_id'=>$product_id, 'status_id'=>$ss['status_id']);
							//$stock -> addStockRealOutNumber($ss['number'], $w);
							$stock -> addStockOutNumber($ss['number'], $w);
							//
							unset($ss['stock_id']); unset($ss['lid']);
							$ss['product_id'] = $product_id;
							$ss['shop_price'] = ($v['supply_price']*$v['amount'])/count($subgoods);
							$outstoc_details[] = $ss;
						}
					}
					//dump($outstoc_details,1,1);
					//插入 shop_outstock & shop_outstock_detail 记录
					$bill = array(
						'lid'=>1,
						'item_no'=>'',
						'bill_no'=>$v['shop_id'].':'.$v['order_sn'],
						'bill_type'=>1,
						'bill_status'=>0,
						'supplier_id'=>0,
						'is_back'=>0,
						'is_cancel'=>0,
						'add_time'=>time(),
						'admin_name'=>'团购插入记录'.$this -> _auth['admin_name'],
						'lock_name'=>''
					);
					$outstockApi -> insertApi($bill, $outstoc_details);
							
					//更新 shop_out_tuan_order.check_stock
					$this -> orderEdit(array('check_stock'=>1), "id=".$v['id']);
					
				}
			}
			//goods_type != 1 正常商品or组合商品
			else{
				//1.普通商品
				if(strlen($v['goods_sn']) == 8){
					//检查库存
					$num = $v['amount']*$v['goods_number'];
					$rs = $stock -> checkPreSaleProductStock($v['p_id'], $num);
					if($rs){
						//查找对应商品批次
						$productBatchStock = $stock -> createSaleOutStock($v['p_id'], $num, false);
						foreach ($productBatchStock as $ss){
							if(!isset( $stock_batch_info[$v['p_id']][$ss['batch_id']]['number'] )){$stock_batch_info[$v['p_id']][$ss['batch_id']]['number'] = 0;}
							$stock_batch_info[$v['p_id']][$ss['batch_id']]['number'] += $ss['number'];//记录减去的商品库存批次量
							$stock_batch_info[$v['p_id']][$ss['batch_id']]['lid'] = $ss['lid'];
							$stock_batch_info[$v['p_id']][$ss['batch_id']]['status_id'] = $ss['status_id'];
							//减对应商品批次的库存
							$w = array('lid'=>$ss['lid'], 'batch_id'=>$ss['batch_id'], 'product_id'=>$v['p_id'], 'status_id'=>$ss['status_id']);
							//$stock -> addStockRealOutNumber($ss['number'], $w);
							$stock -> addStockOutNumber($ss['number'], $w);
						}
						//插入 shop_outstock & shop_outstock_detail 记录
						$bill = array(
							'lid'=>1,
							'item_no'=>'',
							'bill_no'=>$v['shop_id'].':'.$v['order_sn'],
							'bill_type'=>1,
							'bill_status'=>0,
							'supplier_id'=>0,
							'is_back'=>0,
							'is_cancel'=>0,
							'add_time'=>time(),
							'admin_name'=>'团购插入记录'.$this -> _auth['admin_name'],
							'lock_name'=>''
						);
						foreach ($productBatchStock as $xx=>$ss){
							unset($productBatchStock[$xx]['stock_id']);
							unset($productBatchStock[$xx]['lid']);
							$productBatchStock[$xx]['product_id'] = $v['p_id'];
							$productBatchStock[$xx]['shop_price'] = $v['supply_price'];
						}
						$outstockApi -> insertApi($bill, $productBatchStock);
						//更新 shop_out_tuan_order.check_stock
						$this -> orderEdit(array('check_stock'=>1), "id=".$v['id']);
					}
				}
				
				//2.组合商品
				elseif(strlen($v['goods_sn']) == 9){
					//取出这个组合商品
					$groupGoods = $this -> _db -> getGroupGoodsBySN($v['goods_sn']);
					$configs = unserialize($groupGoods['group_goods_config']);
					$subFlag = true;
					
					foreach ($configs as $vv){
			    		$num = $v['amount']*$v['goods_number']*$vv['number'];
						$rs = $stock -> checkPreSaleProductStock($vv['product_id'], $num);
			    		if(!$rs){
			    			$subFlag = false;
			    			break;
			    		}
		    		}
		    		//
					if($subFlag == true){
						$outstoc_details = array();//表 shop_outstock_details 记录
						//减库存
						foreach ($configs as $vv){
							$num = $v['amount']*$v['goods_number']*$vv['number'];
							$product_id = (int)$vv['product_id'];
							if($product_id<1){ break; }
							
							$productBatchStock = $stock -> createSaleOutStock($product_id, $num, false);
							foreach ($productBatchStock as $ss){
								if(!isset( $stock_batch_info[$product_id][$ss['batch_id']]['number'] )){ $stock_batch_info[$product_id][$ss['batch_id']]['number'] = 0; }
								$stock_batch_info[$product_id][$ss['batch_id']]['number'] += $ss['number'];//记录减去的商品库存批次量
								$stock_batch_info[$product_id][$ss['batch_id']]['lid'] = $ss['lid'];
								$stock_batch_info[$product_id][$ss['batch_id']]['status_id'] = $ss['status_id'];
								//减对应商品批次的库存
								$w = array('lid'=>$ss['lid'], 'batch_id'=>$ss['batch_id'], 'product_id'=>$product_id, 'status_id'=>$ss['status_id']);
								//$stock -> addStockRealOutNumber($ss['number'], $w);
								$stock -> addStockOutNumber($ss['number'], $w);
								//
								unset($ss['stock_id']); unset($ss['lid']);
								$ss['product_id'] = $product_id;
								$ss['shop_price'] = ($v['supply_price']*$v['amount'])/count($configs);
								$outstoc_details[] = $ss;
							}
						}
						//dump($outstoc_details,1,1);
						//插入 shop_outstock & shop_outstock_detail 记录
						$bill = array(
							'lid'=>1,
							'item_no'=>'',
							'bill_no'=>$v['shop_id'].':'.$v['order_sn'],
							'bill_type'=>1,
							'bill_status'=>0,
							'supplier_id'=>0,
							'is_back'=>0,
							'is_cancel'=>0,
							'add_time'=>time(),
							'admin_name'=>'团购插入记录'.$this -> _auth['admin_name'],
							'lock_name'=>''
						);
						$outstockApi -> insertApi($bill, $outstoc_details);
								
						//更新 shop_out_tuan_order.check_stock
						$this -> orderEdit(array('check_stock'=>1), "id=".$v['id']);
					}
				}
				//dump($stock_batch_info,1,1);
			}
		}

		//更新shop_out_tuan_batch.stock_batch_info
		$this -> batchUpdate(array('stock_batch_info'=>serialize($stock_batch_info)), "batch = $batch");
		
		//这个批次订单数量
		$tot = $this -> getOutTuanOrderCount(array('batch'=>$batch));//总数量
		$unCheckStockTot = $this -> getOutTuanOrderCount(array('batch'=>$batch,'check_stock'=>0));//没有库存的订单数量
		//更新 shop_out_tuan_batch.check_stock
		if($unCheckStockTot==0){//全部通过库存检查
			$this -> batchUpdate(array('check_stock'=>2), " batch=".$batch);
			return '库存检查成功';
		}elseif($unCheckStockTot==$tot){//全部没通过
			return '此批次订单没有库存';
		}else{//部分通过
			$this -> batchUpdate(array('check_stock'=>1), " batch=".$batch);
			return '此批次订单有部分库存';
		}
	}
	
	/**
	 * 只得到订单计数
	 * 
	 * @param array $search
	 * 
	 * @return int
	 */
	public function getOutTuanOrderCount($search=null) {
		return $this -> _db -> getOutTuanOrderCount($search);
	}
	
	/**
	 * 只删除批次号  shop_out_tuan_batch 
	 * 
	 * @param string $batch
	 */
	public function batchDel($batch) {
		return $this -> _db -> batchDel($batch);
	}
	
	/**
	 * 更新同步到官网的重复订单号
	 * 
	 */
	public function modifyRepeatOrder() {
		$orders = $this -> _db -> modifyRepeatOrder();
	}
	
	/**
	 * 更改订单的减库存状态
	 * 
	 * @param int $id
	 * @param int $st
	 * 
	 */
	public function checkStockChange($id, $st) {
		$this -> _db -> orderEdit(array('check_stock'=>$st), "id=$id");
	}
	
	/**
     * 根据'批次'来修正：
     * 1.表 shop_out_tuan_batch.xz 字段作为标示
     * 2.表中物流公司为空
     * 3.order_batch/transport表中运单号为空   transport表中物流公司为空
     */
	public function xz() {
		set_time_limit(0);
		//每次取出 50 个批次进行操作
		$batchs = $this -> getBatch(array('tongbu'=>'on', 'xz'=>true), 1, 50);
		if($batchs['tot'] < 1){echo '修正完成';exit;}
		$batchs = $batchs['datas'];
		if(is_array($batchs) && count($batchs)){
			//取出物流公司
			$logistics_com = $this -> getLogistic();
			foreach ($logistics_com as $index=>$val){
				$tmp[$val['logistic_code']] = $val['name'];
			}
			$logistics_com = $tmp; unset($tmp);
			//处理批次
			foreach ($batchs as $v){
				//取出批次订单
				$orders = $this -> _db -> getTable('shop_out_tuan_order', 'order_sn,logistics_com,logistics_no', array('batch'=>$v['batch']));
				if($orders['tot'] > 0){
					$orders = $orders['datas'];
					if(is_array($orders) && count($orders)){
						$unfinedOrders = '';
						foreach ($orders as $vv){
							//取出官网订单
							$gw = $this -> _db -> getTable('shop_order', 'order_sn', array('external_order_sn'=>$vv['order_sn']));
							if($gw['tot'] > 0){
								$sn = $gw['datas'][0]['order_sn'];
								//开始更新
								$set = array('logistic_name'=>$logistics_com[$vv['logistics_com']], 'logistic_code'=>$vv['logistics_com'], 'logistic_no'=>$vv['logistics_no']);
								$this -> _db -> updateTable('shop_order_batch', $set, " order_sn='".$sn."' ");
								$this -> _db -> updateTable('shop_transport',   $set, " bill_no='".$sn."' ");
							}else{
								$unfinedOrders .= '_'.$vv['order_sn'].'_';
							}
						}
						//记录
						if($unfinedOrders != ''){$unfinedOrders = '_未找到的订单：'.$unfinedOrders;}
						echo $v['batch'].'_ok'.$unfinedOrders.'<br>';
					}else{
						echo $v['batch'].'没有订单';
					}
				}else{
					echo '批次'.$v['batch'].'没有订单';
				}
				//更新批次 xz=1
				$this -> _db -> batchUpdate(array('xz'=>1), " batch='".$v['batch']."' ");
			}
		}
		echo '<br>本次操作完成'; exit;
	}
	
	
	/**************************************
	 * 订单分物流
	 * 
	 * @param array $outOrderBatch
	 * 
	 * @return array
	 */
	public function divOrderToLogistics($outOrderBatch) {
		$tot = $outOrderBatch['tot'];
    	$outOrderBatch = $outOrderBatch['datas'];
    	//物流公司
		$logistic = $this -> getLogistic();
		foreach ($logistic as $index=>$val){
			$tmp[$val['logistic_code']] = $val['name'];
		}
		$logistic = $tmp; unset($tmp);
    	//分割地址
    	foreach($outOrderBatch as $k=>$v){
    		$divArea = $this -> areaPlug($v['addr']);
			//整理一下详细地址
			$p = 1;
			foreach ($divArea as $kkk=>$vvv){
				$tmp[$p]['ids'] = $kkk;
				if($vvv==''){
					$tmp[$p]['area'] = '未匹配到_';
				}else{
					$tmp[$p]['area'] = $vvv;
				}
				$p++;
			}unset($divArea);
			$outOrderBatch[$k]['divarea'] = $tmp;
			$outOrderBatch[$k]['province_id'] = $tmp[1]['ids'];
			//物流公司名称
			$outOrderBatch[$k]['logistic_name'] = $logistic[$outOrderBatch[$k]['logistics_com']];
			if($outOrderBatch[$k]['logistics_no'] == ''){
				$outOrderBatch[$k]['logistics_no'] = '888';
			}
    	}
    	//取得物流策略
    	$logistic_api = new Admin_Models_API_Logistic();
    	$logistics_strategy_array = $logistic_api -> getLogisticPolicy();
    	//
    	$provinceOrders = array();
    	$provinceOrdersCount = array();
    	foreach($outOrderBatch as $k=>$v){
    		if(array_key_exists($v['province_id'], $logistics_strategy_array)){
    			$p = $logistics_strategy_array[$v['province_id']]['code'][0];
    		}else{
    			$p = 'ems';
    		}
    		$provinceOrders[$p][] = $v;
    		$provinceOrdersCount[$p]['ct'] += 1;
    		$provinceOrdersCount[$p]['logistics_com'] = $logistic[$p];
    	}
    	//
    	return array('datas'=>$provinceOrders, 'pcount'=>$provinceOrdersCount, 'tot'=>$tot);
	}
	
	/**
	 * 新订单打印
	 * 
	 * @param string $batch
	 * @param bool   $reprint
	 * 
	 * @return array
	 */
	public function newBatchPrintData($batch, $reprint=false) {
		if(!$reprint){
			$check = $this -> getBatch(array('logistics'=>'lt2', 'tongbu'=>'off', 'check_stock'=>'gt0', 'batch'=>$batch));
    		if($check['tot'] < 1){ unset($check); exit('批次已经发货，或批次未减库存，或批次不存在'); }
		}
		if(!$reprint){
	    	$search = array('batch'=>$batch, 'logistics'=>'off', 'print'=>'off', 'ischeat'=>'off', 'check_stock'=>1, 'status'=>'on');
		}else{
			$search = array('batch'=>$batch, 'ischeat'=>'off', 'check_stock'=>1, 'status'=>'on');
		}
    	$outOrderBatch = $this -> getOutTuanOrder($search, 1, 1, null, true);
    	if($outOrderBatch['tot'] < 1){ exit('此批次没有可打印订单'); }
    	return $this -> divOrderToLogistics($outOrderBatch);
	}
	
	/**
	 * 新得到批次订单，并且分快递公司，去除刷单
	 * 
	 * @param arrar $search
	 * 
	 * @return array
	 */
	public function newGetOutTuanOrder($search = array()) {
		$orders = $this -> getOutTuanOrder($search, 1, 1, null, true);
		if($orders['tot'] < 1){return 'nofind';}
		return $this -> divOrderToLogistics($orders);
	}
	
	/**
	 * 新填充快递单号
	 * 
	 * @param string $batch
	 * @param string $logistic_com
	 * @param string $first_no
	 * @param string $re  //手选快递公司代码
	 */
	public function newFillno($batch, $logistics_com, $first_no, $re) {
		$rs = $this -> newBatchPrintData($batch);
		if(!is_array($rs['datas'][$logistics_com]) || !count($rs['datas'][$logistics_com])){exit('批次'.$batch.'没有对应'.$logistics_com.'的订单');}
		$rs = $rs['datas'][$logistics_com];
		//判断是否为 手选快递公司
		$logistics_com = ($logistics_com == $re) ? $logistics_com : $re;
		//
		foreach($rs as $k=>$v){
			//$set=array('logistics'=>1, 'print'=>1, 'logistics_com'=>$logistics_com, 'logistics_no'=>$first_no, 'logistics_time'=>time(), 'print_user'=>$this -> _auth['admin_id']);
			$set=array('logistics'=>0, 'print'=>1, 'logistics_com'=>$logistics_com, 'logistics_no'=>$first_no, 'logistics_time'=>time(), 'print_user'=>$this -> _auth['admin_id']);
			$this -> orderEdit($set, "id = ".$v['id']."  and logistics=0");
			$first_no += 1;
		}
		//检查这个批次是否全部打印完成，如果完成，更新shop_out_tuan_batch表
		$this -> newCheckBatchLogistics($batch);
		//
		exit('ok');
	}
	
	/**
	 * 新检查批次是否已经全部打印完毕，更新shop_out_tuan_batch表
	 * 
	 * @param string $batch
	 * 
	 * @return array(批次订单总数, 批次已经发货的数量, 批次的物流状态)
	 */
	public function newCheckBatchLogistics($batch) {
		$batchTot = $this -> _db -> getOutTuanOrderCount(array('batch'=>$batch));
		$batchLoged = $this -> _db -> getOutTuanOrderCount(array('batch'=>$batch, 'logistics'=>1));
		if($batchTot == $batchLoged){
			$logistics = 2;
		}elseif($batchLoged == 0){
			$logistics = 0;
		}else{
			$logistics = 1;
		}
		$this -> batchUpdate(array('logistics'=>$logistics), "batch=$batch");
		return array('batchOrderTot'=>$batchTot, 'batchOrderLogisticsed'=>$batchLoged, 'logistics'=>$logistics);
	}
	
	/**
	 * 修正批次同步过缺少logistic_name、logistic_code的情况
	 * 
	 * @param sring $batch
	 * 
	 * @return string
	 */
	public function batchLogisticComCode($batch) {
		$batch = trim($batch);
		if($batch == ''){ return '请输入批次号'; }
		$orders = $this -> getOutTuanOrder(array('batch'=>$batch), 1, 1, null, true);
		if($orders['tot'] < 1){ return '此批次没有订单'; }
		$orders = $orders['datas'];
		//取出快递公司
		$logistic = $this -> getLogistic();
		foreach ($logistic as $index=>$val){
			$tmp[$val['logistic_code']] = $val['name'];
		}
		$logistic = $tmp; unset($tmp);
		//
		foreach ($orders as $v){
			//取得 shop_order中对应的官网order_sn
			$sn = $this -> _db -> getOrderByExtentOrderSn($v['order_sn']);
			if($sn){
				$set = array('logistic_name'=>$logistic[$v['logistics_com']], 'logistic_code'=>$v['logistics_com'], 'logistic_no'=>$v['logistics_no']);
				//更新 shop_order_batch表的logistic_name、logistic_code字段
				$this -> _db -> updateTable('shop_order_batch', $set, "order_sn='$sn'");
				//更新 shop_transport表的logistic_name、logistic_code字段
				$this -> _db -> updateTable('shop_transport', $set, "bill_no='$sn'");
			}
		}
		return 'ok';
	}
	
	/**
	 * 按批次删除重复同步的订单
	 * 
	 * @param sring $batch
	 * 
	 * @return string
	 */
	public function delTongbuRepeatOrder($batch) {
		$batch = trim($batch);
		if($batch == ''){ return '请输入批次号'; }
		$orders = $this -> getOutTuanOrder(array('batch'=>$batch), 1, 1, null, true);
		if($orders['tot'] < 1){ return '此批次没有订单'; }
		$orders = $orders['datas'];
		foreach ($orders as $v){
			$order_sn = $this -> _db -> getTable('shop_order', 'order_sn', array('external_order_sn'=>$v['order_sn']));
			if($order_sn['tot'] > 1){
				$order_sn = $order_sn['datas'];
				array_shift($order_sn);
				if(is_array($order_sn) && count($order_sn)){
					foreach ($order_sn as $sn){
						/**
						 * 删除下表中相应的记录
						 * `shop_order`
						 * `shop_order_batch`
						 * `shop_order_batch_goods`
						 * `shop_order_batch_adjust`
						 * `shop_transport`
						 * `shop_transport_track`
						 */
						$this -> _db -> deleteTongbuOrder($sn['order_sn']);
					}
				}
			}
		}
		return 'ok';
	}
	
	/**
     * 改变批次物流公司代码logistics_com
     * 
     * @param string $batch
     * @param string $flc
     * @param string $nlc
     * 
     * @return string
     */
	public function changeBatchLogisticsCom($batch, $flc, $nlc) {
		$this -> orderEdit(array('logistics_com'=>$nlc), "batch='$batch' and logistics_com='$flc'");
		return 'ok'; 
	}
	
	/**
	 * 修改同步后有的订单调整金额没加
	 * 
	 */
	public function modifyPayAdjust() {
        $sql = "select b.order_sn,b.price_pay,b.price_payed 
        from 
        shop_order as a left join 
        shop_order_batch as b on a.order_id=b.order_id 
        where 
        a.shop_id in (11,17) and b.status=0 and b.status_logistic=4";
        $list = $this -> _db -> execSql($sql);
        if (is_array($list) && count($list)){
        	foreach ($list as $v){
        		$mn = $v['price_payed'] - $v['price_pay'];
        		if($mn != 0){
        			//插入 order_batch_adjust
        			$row = array();
        			$row = array(
						'order_sn' => $v['order_sn'],
						'batch_sn' => $v['order_sn'],
						'money' => $mn,
						'add_time'=> time()
					);
					$this -> insertIntoOrderBatchAdjust($row);
					//更新状态   shop_order_batch.status_logistic=4, shop_order_batch.status_pay=2
					$this -> _db -> updateTable('shop_order_batch', array('status_logistic'=>4, 'status_pay'=>2), " order_sn='{$v['order_sn']}' ");
        		}
        	}
        	return 'ok';
        }else{
        	return '没有需要修改的订单';
        }
	}
    
	public function checkIsBillFilled($batch_no,$lgc_type){

        $flag = true;
        $rs = $this -> newBatchPrintData($batch_no, false);
        
        $data = $rs['datas'][$lgc_type];
        
        foreach ($data as $k=>$v){
           if(empty($v['logistics_com'])){
               $flag = false;
               break;
           }
        }

        return $flag;
        
	}

	/**
	 * 根据物流公司代号取得物流公司名称
	 * @param string $logistics_com
	 */
	public function getLgcNameByLgcCom($logistics_com){
	    $conf = array('yt'=>'圆通','ems'=>'EMS');
	    return $conf[$logistics_com];
	}
	
}