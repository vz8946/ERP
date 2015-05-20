<?php
class UnionController extends Zend_Controller_Action
{
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _api = new Shop_Models_API_Union();
	}
	/**
     * 订单请求接口
     *
     * @return void
     */
    public function syncAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);        
        $user_name = $this -> _request -> getParam('unionId', null);
        $password = $this -> _request -> getParam('pwd', null);
        $starttime = $this -> _request -> getParam('starttime', date('Y-m-d',time()));
		$yyyymmdd = $this -> _request -> getParam('yyyymmdd', null);
        $endtime = $this -> _request -> getParam('endtime', null);
		if($yyyymmdd && !$endtime){
			$endtime=$yyyymmdd ;
		}
        $this -> _api -> syncApi($user_name, $password, $starttime, $endtime);
        exit;
    }

	/**
     * 领科特订单查询接口
     *
     * @return void
     */
    public function linktsyncAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);        
        $user_name = 'lingkt';
        $password = 'lingkt999';
        $starttime = date('Y-m-d',time());
		$starttime = $endtime = $this -> _request -> getParam('yyyymmdd', date('Y-m-d',time()));
        $this -> _api -> syncApi($user_name, $password, $starttime, $endtime);
        exit;
    }

	/**
     * 对外查询接口
     *
     * @return void
     */
    public function unlistAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);        
        $uname = $this -> _request -> getParam('uname', null);
        $upwd = $this -> _request -> getParam('upwd', null);
        $starttime = $this -> _request -> getParam('starttime', date('Y-m-d',time()));
		$yyyymmdd = $this -> _request -> getParam('yyyymmdd', null);
        $endtime = $this -> _request -> getParam('endtime', null);
		if($yyyymmdd && !$endtime){
			$endtime=$yyyymmdd ;
		}
        $this -> _api -> unlist($uname, $upwd, $starttime, $endtime);
        exit;
    }

	/**
     * 一起发入口程序
     *
     * @return void
     */

    public function yiqifaAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);    
        $data = $this -> _request -> getParams();
        $cid  = trim($data["cid"]);
        $wi  = trim($data["wi"]);
        $url  = trim($data["url"]);
        $jumpurl=$url."?u=9133&a={$wi}"; 
        $time = time() + 84600 * 31;
        setcookie('cid',$cid, $time, '/');
        setcookie('wi',$wi, $time, '/');
        Header("Location: $jumpurl");
	}

	/**
     * 51返利订单查询接口程序
     *
     * @return void
     */
    public function fanliOrderAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout); 
        $begin_date= $this -> _request -> getParam('begin_date');
        $end_date = $this -> _request -> getParam('end_date');
        if ( ($begin_date <= 0) || ($end_date <= 0) ) {
            $msg = '日期错误！';
            $this -> _api -> writeSyncLog($msg);
            $this -> _api -> httpHeader($msg);
        }
        $fromDate = strtotime($begin_date);
        $toDate = strtotime($end_date) + 86400;
        $information = new Union_Models_API_Information();
        $where = array('from_date' => $fromDate, 'to_date' => $toDate);
        $temp = $information -> getProfitSync($where, 13);
        foreach($temp as $k=>$var){
            if( $var['product_id'] >0 ){
                $order_goods[$var['order_sn']][]=$var;
            }
        }
        $xml = "<fanli_data version=\"3.0\"  encoding=\"utf-8\">\n";
              foreach($temp as $key=>$var){
                $flOrder=array();
                if(!in_array($var['order_sn'],$flOrder)){
                        $flOrder[]=$var['order_sn'];
                        $order_add_time = date('Y-m-d H:i:s', $var['order_add_time']);   
                        if($var['pay_type']=='cod'){
                            $pay_type='2';
                        }else{
                            $pay_type='1';
                        }
                        $untemp =unserialize($var['code_param']);
                        if($untemp['channel_id']=='51fanli'){

                        }elseif(strstr($untemp['fanli_username'], '@51fanli')){

                        }
                        foreach($order_goods[$var['order_sn']] as $k=>$v){                   
                            if($v['product_id'] >0 ){
                              $var['order_number']+=$v['number'];
                            }                              
                        }
                        $pass_code=md5(strtolower($var['order_sn'].'jiankang'.$untemp['fanli_u_id'].'51fanlijiankang'));
                        $xml .= "<order order_time='".$order_add_time."' order_no='".$var['order_sn']."' shop_no='1jiankang' total_price='".$var['order_price_goods']."' total_qty='".$var['order_number']."' shop_key='51fanli1jiankang' u_id='".$untemp[fanli_u_id]."'        username='".$untemp[fanli_username]."' is_pay='".$var['status_pay']."' pay_type='".$pay_type."' order_status='1' deli_name='' deli_no ='' tracking_code='".$var['user_param']."' pass_code='".$pass_code."' >";
                        $xml .= "<products_all>\n";
                         foreach($order_goods[$var['order_sn']] as $k=>$v){
                                    $xml .= "<product>\n";
                                    $xml .= "<product_id>".$v['product_id']."</product_id>\n";
                                    $xml .= "<product_url>http://www.1jiankang.com/goods-".$v['product_id'].".html</product_url>";
                                    $xml .= "<product_qty>".$v['number']."</product_qty>\n";
                                    $xml .= "<product_price >".$v['sale_price']."</product_price>\n";
                                    $xml .= "<product_comm>0</product_comm>\n";
                                    $xml .= "<comm_no>11</comm_no>\n";
                                    $xml .= "</product>\n";
                         }
                        $xml .= "</products_all>\n";
                        $xml .= "</order>\n";
                }
              }
            $xml .= "</fanli_data>\n";
            header('Content-Type: text/xml');
            echo($xml);
            exit;
	}

	/**
     * 领科特广告文件
     *
     * @return void
     */
    public function linktechAction()
    {
		$this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $data = $this -> _request -> getParams();
        $a_id  = $data["a_id"];
        $m_id  = $data["m_id"];
        $c_id  = $data["c_id"];
        $l_id  = $data["l_id"];
        $l_type1 = $data["l_type1"];
        $rd    = $data["rd"];
        $url   = $data["url"];
		// $url   = urlencode($data["url"]);
        $nid="$a_id|$c_id|$l_id|$l_type1|";
	    $jumpurl=$url.'?u=9096&nid='.$nid;
        Header('HTTP/1.1 301 Moved Permanently');
        Header("Location: $jumpurl");
        exit;
    }

    
    /**
     * 成果网订单查询接口
     *
     * @return void
     */
    public function chanetsyncAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $user_name = $this -> _request -> getParam('user', null);
        if ( $user_name == 'chanet') {
            $password = 'chanet999';
        }
        else    $password = '';
        
        $start= $this -> _request -> getParam('start');
        $end = $this -> _request -> getParam('end');
        
        $starttime = mktime(substr($start,8,2), substr($start,10,2), substr($start,12,2), substr($start,4,2), substr($start,6,2), substr($start,0,4));
        $endtime = mktime(substr($end,8,2), substr($end,10,2), substr($end,12,2), substr($end,4,2), substr($end,6,2), substr($end,0,4));
        $starttime = date('Y-m-d H:i:s', $starttime);
        $endtime = date('Y-m-d H:i:s', $endtime - 86400);   //api中会自动加86400
		
		$unixtime = $this -> _request -> getParam('unixtime', 0);
		if ((time() - $unixtime) > 600) {
		    $msg = '已超时！';
            $this -> _api -> writeSyncLog($msg);
            $this -> _api -> httpHeader($msg);
		}
		$key='k6IPPhuazjg8yQxE';
		$chanet_sig = $this -> _request -> getParam('sig', '');
		$sig = md5("user={$user_name}&start={$start}&end={$end}&unixtime={$unixtime}&key={$key}");
		if ( $chanet_sig != $sig ) {
		    $msg = '参数传递错误！';
            $this -> _api -> writeSyncLog($msg);
            $this -> _api -> httpHeader($msg);
		}
		
        $this -> _api -> syncApi($user_name, $password, $starttime, $endtime);
        exit;
    }
    /**
     * N返利网订单查询接口
     *
     * @return void
     */
    public function nfanlisyncAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $user_name = $this -> _request -> getParam('unionid', null);
        if ( $user_name == 'nfanli.com') {
            $password = 'nfanli';
        }
        else    $password = '';
        
        $date= $this -> _request -> getParam('date');
        $begin_date= $this -> _request -> getParam('begin_date');
        $end_date = $this -> _request -> getParam('end_date');
        if ( $date > 0 ) {
            $starttime = date('Y-m-d', $date);
            $endtime = $starttime;
        }
        else {
            if ( ($begin_date <= 0) || ($end_date <= 0) ) {
                $msg = '日期错误！';
                $this -> _api -> writeSyncLog($msg);
                $this -> _api -> httpHeader($msg);
            }
            $starttime = date('Y-m-d H:i:s', $begin_date);
            $endtime = date('Y-m-d H:i:s', $end_date - 86400);   //api中会自动加86400
        }
        
        $this -> _api -> syncApi($user_name, $password, $starttime, $endtime);
        exit;
    }
    
    /**
     * N返利网商品信息查询接口
     *
     * @return void
     */
    public function nfanliGoodsAction(){
        $this -> _helper -> viewRenderer -> setNoRender();
        
        $postdata = $this -> _request -> getPost();
        if ( $postdata ) {
            
        }
        exit;
    }
    
    /**
     * 多麦网订单查询接口
     *
     * @return void
     */
    public function duomaisyncAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $user_name = $this -> _request -> getParam('user', null);
        if ( $user_name == 'duomai.com') {
            $password = 'duomai';
        }
        else    $password = '';
        
        $date= $this -> _request -> getParam('date');
        $begin_date= $this -> _request -> getParam('begin_date');
        $end_date = $this -> _request -> getParam('end_date');
        if ( $date > 0 ) {
            $starttime = date('Y-m-d', $date);
            $endtime = $starttime;
        }
        else {
            if ( ($begin_date <= 0) || ($end_date <= 0) ) {
                $msg = '日期错误！';
                $this -> _api -> writeSyncLog($msg);
                $this -> _api -> httpHeader($msg);
            }
            $starttime = date('Y-m-d H:i:s', $begin_date);
            $endtime = date('Y-m-d H:i:s', $end_date - 86400);   //api中会自动加86400
        }
        
        $this -> _api -> syncApi($user_name, $password, $starttime, $endtime);
        exit;
    }
    
    /**
     * 一淘商品索引接口(全量索引)
     *
     * @return void
     */
    public function etaoFullAction()
    {
        $this -> _db = Zend_Registry::get('db');
        $current_time = date('Y-m-d H:i:s', time());

        $sql = "SELECT goods_sn,price,onsale FROM `shop_goods` where onsale = 0 and is_del = 0 ORDER BY goods_id";
        $goods_data = $this -> _db -> fetchAll($sql);
        $item_index = '';
        foreach($goods_data as $goods) {
            if ($goods['onsale'] == 0) {
                $item_index .= "<outer_id action=\"upload\">{$goods['goods_sn']}</outer_id>\r\n";
                $history .= "{$goods['goods_sn']}/{$goods['price']}\r\n";
            }
        }
        
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                  <root>
                    <version>1.0</version>
                    <modified>{$current_time}</modified>
                    <seller_id>垦丰</seller_id>
                    <cat_url>http://www.1jiankang.com/etao/SellerCats.xml</cat_url>
                    <dir>http://www.1jiankang.com/etao/item/</dir>
                    <item_ids>
                      {$item_index}
                    </item_ids>
                  </root>";
        file_put_contents("etao/FullIndex.xml", $xml);
        file_put_contents("etao/history.log", $history);
        
        $this -> createEtaoGoodsXML();
        exit;
    }
    
    /**
     * 一淘商品索引接口(增量索引)
     *
     * @return void
     */
    public function etaoIncrementAction()
    {
        $this -> _db = Zend_Registry::get('db');
        $current_time = date('Y-m-d H:i:s', time());
        
        $sql = "SELECT goods_sn,price,onsale FROM `shop_goods` WHERE onsale = 0 and is_del = 0 ORDER BY goods_id";
        $goods_data = $this -> _db -> fetchAll($sql);
        foreach( $goods_data as $goods ) {
            $goods_info[$goods['goods_sn']]['price'] = $goods['price'];
            $goods_info[$goods['goods_sn']]['flag'] = 0;
        }
        
        $content = file_get_contents("etao/history.log");
        $lines = explode("\r\n", $content);
        foreach ( $lines as $line ) {
            if (trim($line) == '')  continue;
            
            $temparr = explode('/', $line);
            if ( !is_array($goods_info[$temparr[0]]) ) {    //下架
                $item_index .= "<outer_id action=\"delete\">{$temparr[0]}</outer_id>\r\n";
            }
            else {
                if ( $goods_info[$temparr[0]]['price'] != $temparr[1] ) {  //价格调整
                    $item_index .= "<outer_id action=\"upload\">{$temparr[0]}</outer_id>\r\n";
                    $this->createEtaoGoodsXML($temparr[0]);
                }
                $goods_info[$temparr[0]]['flag'] = 1;
                $history .= "{$temparr[0]}/{$goods_info[$temparr[0]]['price']}\r\n";
            }
        }
        
        foreach( $goods_data as $goods ) {
            if ( $goods_info[$goods['goods_sn']]['flag'] == 0 ) {   //上架
                $item_index .= "<outer_id action=\"upload\">{$goods['goods_sn']}</outer_id>\r\n";
                $this->createEtaoGoodsXML($goods['goods_sn']);
                $history .= "{$goods['goods_sn']}/{$goods['price']}\r\n";
            }
        }
        
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                  <root>
                    <version>1.0</version>
                    <modified>{$current_time}</modified>
                    <seller_id>垦丰</seller_id>
                    <cat_url>http://www.1jiankang.com/etao/SellerCats.xml</cat_url>
                    <dir>http://www.1jiankang.com/etao/item/</dir>
                    <item_ids>
                      {$item_index}
                    </item_ids>
                  </root>";
        file_put_contents("etao/IncrementIndex.xml", $xml);
        file_put_contents("etao/history.log", $history);
        
        exit;
    }
    
    /**
     * 一淘商品接口(分类表)
     *
     * @return void
     */
    public function etaoCatAction()
    {
        $this -> _db = Zend_Registry::get('db');
        $current_time = date('Y-m-d H:i:s', time());
        
        $sql = "SELECT cat_id,cat_name,cat_path,parent_id FROM `shop_goods_cat` WHERE display = 1   ORDER BY cat_id";
        $cat_data = $this -> _db -> fetchAll($sql);
        
        $top_cat = array();
        foreach($cat_data as $cat) {
            if ($cat['parent_id'] == 0) {
                $top_cat[] = $cat;
            }
        }
        for ($i = 0; $i < count($top_cat); $i++) {
            $cat_xml .= "<cat>
                           <scid>{$top_cat[$i]['cat_id']}</scid>
                           <name>{$top_cat[$i]['cat_name']}</name> 
                           <cats>\r\n";
            foreach($cat_data as $cat) {
                if ($cat['parent_id'] == $top_cat[$i]['cat_id']) {
                    $cat_xml .= "<cat>
                                   <scid>{$cat['cat_id']}</scid>
                                   <name>{$cat['cat_name']}</name>
                                 </cat>\r\n";
                }
            }
            $cat_xml .= "</cats> 
                         </cat>\r\n";
        }
        
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                  <root>
                    <version>1.0</version>
                    <modified>{$current_time}</modified>
                    <seller_id>垦丰</seller_id>
                    <seller_cats>
                      {$cat_xml}
                    </seller_cats>
                  </root>";
        file_put_contents("etao/SellerCats.xml", $xml);
        
        exit;
    }
    
    /**
     * 生成一淘商品详情
     *
     * @return void
     */
    private function createEtaoGoodsXML($sn = null)
    {
        $this -> _db = Zend_Registry::get('db');
        
        if ( $sn != null ) {
            $where = "WHERE goods_sn = '{$sn}'";
        }
        else {
            $where = "WHERE onsale = 0 ";
        }
        
        $sql = "SELECT t2.goods_id,img_url FROM `shop_goods_img` AS t1 LEFT JOIN `shop_goods` AS t2 ON t1.product_id = t2.product_id {$where}";
        $img_data = $this -> _db -> fetchAll($sql);
        $img_info = '';
        foreach( $img_data as $img ) {
            $img_info[$img['goods_id']][] = $img['img_url'];
        }
        
        $sql = "SELECT t2.*,C.cat_name,B.brand_name,B.as_name FROM `shop_goods` AS t2 INNER JOIN `shop_goods_cat`  AS C  ON t2.view_cat_id = C.cat_id INNER JOIN shop_product as t3 on t2.product_id = t3.product_id LEFT JOIN `shop_brand` AS B ON t3.brand_id = B.brand_id {$where} ORDER BY goods_id";
        $goods_data = $this -> _db -> fetchAll($sql);
        foreach( $goods_data as $goods ) {
            $img_xml = '';
            if ( $img_info[$goods['goods_id']] ) {
                for ( $i = 0; $i < count($img_info[$goods['goods_id']]); $i++ ) {
                    $img_xml .= "<img>http://www.1jiankang.com/{$img_info[$goods['goods_id']][$i]}</img>\n";
                }
            }
            
            $desc = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $goods['introduction']);
            $desc = preg_replace("'&([a-z]+?);'si", "", $desc);
            $desc = mb_substr($desc, 0, 500, 'utf-8');
            $goods_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                            <item>
                              <seller_id>垦丰</seller_id>
                              <outer_id>{$goods['goods_sn']}</outer_id>
                              <title><![CDATA[{$goods['goods_name']}]]></title>
                              <type>fixed</type>
                              <price>{$goods['price']}</price>
                              <desc><![CDATA[{$desc}]]></desc>
                              <brand>{$goods['brand_name']}</brand>
                              <tags>{$goods['brand_name']}\\{$goods['cat_name']}</tags>
                              <image>http://www.1jiankang.com/{$goods['goods_img']}</image>
                              <more_images>
                                {$img_xml}
                              </more_images>
                              <scids>{$goods['view_cat_id']}</scids>
                              <post_fee>0</post_fee>
                              <props></props>
                              <showcase></showcase>
                              <href>http://www.1jiankang.com/b-{$goods['as_name']}/detail{$goods['goods_id']}.html</href>
                            </item>";
            file_put_contents("etao/item/{$goods['goods_sn']}.xml", $goods_xml);
        }
    }
    
    /**
     * 360CPS联盟入口
     *
     * @return void
     */
    public function cps360Action()
    {
        if ( $_REQUEST['bid'] != 20120493 ) exit;
        
        $union['qihoo_id'] = $_REQUEST['qihoo_id'];
        $union['qid'] = $_REQUEST['qid'];
        $union['ext'] = $_REQUEST['ext'];
        $union = serialize($union);
        
        setcookie('union_param', $union, time() + 84600 * 30, '/');
        
        $url = $_REQUEST['url'];
        if ( !$url )    $url = 'http://www.1jiankang.com';
        
        header("location: {$url}?u=43213");
        exit;
	}
	
	/**
     *  360CPS订单查询接口
     *
     * @return void
     */
    public function cps360syncAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		
		$bid = $this -> _request -> getParam('bid', null);
		if ( $bid != 20120493 ) exit;
		
		$user_name = '360union';
		$password = '111111';
		
        $starttime = $this -> _request -> getParam('start_time');
        $endtime = $this -> _request -> getParam('end_time');

        if ( $starttime && $endtime ) {
            $endtime = date('Y-m-d H:i:s', strtotime($endtime) - 86400);
        }
        else {
            exit;   //暂不处理   
        }
        
        $this -> _api -> syncApi($user_name, $password, $starttime, $endtime);
        exit;
    }
    
    /**
     * 上海导购订单查询接口
     *
     * @return void
     */
    public function shunionSyncAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$user_name = 'shunion_1jiankang';
		$shop_key = '0dcb2054fac4f29e';
		$begin_date = $this -> _request -> getParam('begin_date');
		$end_date = $this -> _request -> getParam('end_date');
		if ($this -> _request -> getParam('sign') != md5($user_name.$begin_date.$end_date.$shop_key)) {
		    exit;
		}
        if (strlen($end_date) == 10) {
		    $end_date .= ' 23:59:59';
		}
        $password = 'shunion';
        
        if (strtotime($begin_date) <= 0 || strtotime($end_date) <= 0) {
            $msg = '日期错误！';
            $this -> _api -> writeSyncLog($msg);
            $this -> _api -> httpHeader($msg);
        }
        
        $starttime = $begin_date;
        $endtime = date('Y-m-d H:i:s', strtotime($end_date) - 86400);   //api中会自动加86400
        $this -> _api -> syncApi($user_name, $password, $starttime, $endtime);
        exit;
    }
    
    /**
     *  渠道订单双向同步(cron调用)
     *
     * @return void
     */
    public function externalOrderSyncAction()
    {
        $adminAuthApi = new Admin_Models_API_Auth(); 
        $adminAuthApi -> cronCertification();
        
        $api = new Admin_Models_API_Shop();
        $currentTime = time();
        $datas = $api -> get("status = 0 and shop_type in ('taobao','jingdong','yihaodian','dangdang')", 'shop_id,shop_type,config');
        
        if ( !$datas['list'] )  exit;
        
        foreach ( $datas['list'] as $shop ) {
            $shopID = $shop['shop_id'];
            $shopAPI = Custom_Model_Shop_Base::getInstance($shop['shop_type'], unserialize($shop['config']));
            $shopAPI -> initSyncLog();
            $api -> initSyncLog();
            $api -> syncOrder($shopAPI, $shopID,  null, true);
            $logID = $api -> addSyncLog($shopID, 'sync', $shopAPI -> _startTime, $shopAPI -> _log.$api -> _log);
        }
        
        exit;
    }
    /**
     * 智推网入口
     *
     * @return void
     */
    public function zhituiAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		
        $data = $this -> _request -> getParams();
        $a_id  = trim($data["a_id"]);
        $subid  = trim($data["subid"]);
        $url  = urldecode(trim($data["url"] ? $data["url"] : 'http://www.1jiankang.com'));
        
        $jumpurl=$url."?u=61044&a={$subid}";
        header("Location: $jumpurl");
	}
	
	/**
     * 智推网订单查询接口
     *
     * @return void
     */
    public function zhituisyncAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $user_name = $this -> _request -> getParam('source', null);
        if ( $user_name == 'zhitui') {
            $password = 'zhitui';
        }
        else    $password = '';

        $date= $this -> _request -> getParam('date');
        $begin_date= strtotime($this -> _request -> getParam('starttime'));
        $end_date = strtotime($this -> _request -> getParam('endtime'));
        
        $starttime = date('Y-m-d H:i:s', $begin_date);
        $endtime = date('Y-m-d H:i:s', $end_date - 86400);   //api中会自动加86400
        
        $this -> _api -> syncApi($user_name, $password, $starttime, $endtime);
        exit;
    }
}