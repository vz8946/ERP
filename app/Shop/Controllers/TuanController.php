<?php
class TuanController extends Zend_Controller_Action
{
	/**
     * @var Shop_Models_API_Tuan
     */
    private $_api = null;
    private $_api_goods = null;
    private $user = null;

	public function init()
    {
		$this -> _api = new Shop_Models_API_Tuan();
		$this -> _api_goods = new Shop_Models_API_Goods();
        $_auth = Shop_Models_API_Auth :: getInstance();
	    $this -> user = $_auth -> getAuth();
	    Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout); //卸载头部 尾部
	}

    /**
     * 团购首页
     *
     * @return   void
     */
    public function indexAction()
    {
        $search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
        $search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
        $page = $search['page'] ? (int)$search['page'] : 1;
        $type = $search['type'] ? (int)$search['type'] : 1;
        $currentTime = time();
        if ( $type == 1 ){

            $where = "t1.start_time <= $currentTime and t1.end_time > $currentTime and t1.status = 0";
            $datas = $this -> _api -> get( $where, 't1.*,t2.img1,t3.market_price,t3.goods_img', 'start_time DESC' );
        }
        else {
            $where = "t1.end_time < $currentTime and t1.status = 0";
            $datas = $this -> _api -> get( $where, 't1.*,t2.img1,t3.market_price,t3.goods_img', 'end_time DESC', $page, 8 );
        }

        $total = $datas['total'];
        $tuanData = $datas['list'];
      //  if ( ($total == 1) && ($type == 1) ) {
       //     $this -> _helper -> redirector -> gotoUrl('/tuan/view/id/'.$tuanData[0]['tuan_id']);
        //    exit;
        //}

        if ( $total > 0 ) {
            foreach ( $tuanData as $index => $tuan ) {
                $tuanData[$index]['count'] = $this -> _api -> getOrderTuanGoodsCount( $tuan['tuan_id'] );
                $tuanData[$index]['status'] = $this -> _api -> getTuanStatus( $tuanData[$index] );
                $tuanData[$index]['start_time'] = date( 'Y-m-d', $tuanData[$index]['start_time'] );
                $end_time = $tuanData[$index]['end_time'];
                $tuanData[$index]['end_time'] = $end_time-time();;
                $tuanData[$index]['end_time_2'] = date( 'm/d/Y H:i:s', $end_time );
               $tuanData[$index]['discount'] = $tuan['market_price'] == 0 ? 0 : round(   $tuan['price'] /$tuan['market_price']* 10, 1 );
                $tuanData[$index] = $this -> _api -> getGoodsImg($tuanData[$index]);
            }
        }

        $this -> view -> datas = $tuanData;
        $this -> view -> type = $type;

        if ( $type == 1 ) {
            $this -> view -> cur_place = 'today';
            echo $this -> view -> render('tuan/index.tpl');
        }
        else {
            $pageNav = new Custom_Model_PageNav($total, 8);
            $this -> view -> pageNav = $pageNav -> getPageNavigation();
            $this -> view -> cur_place = 'old';
            echo $this -> view -> render('tuan/old.tpl');
        }
        exit;
    }

    /*
     *
     * 往期团购
     * */
     public function prevAction(){
     	$this -> view -> cur_place = 'prev';
     	 $search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
        $page = $search['page'] ? (int)$search['page'] : 1;
        $type = $search['type'] ? (int)$search['type'] : 1;
        $currentTime = time();
        if ( $type == 1 ){

            $where = "t1.start_time > $currentTime and t1.end_time > $currentTime and t1.status = 0";
            $datas = $this -> _api -> get( $where, 't1.*,t2.img1,t2.goods_id as gid,t3.market_price,t3.goods_img', 'start_time DESC ' , $page, 8 );
        }
        $total = $datas['total'];
        $tuanData = $datas['list'];
        if ( $total > 0 ) {
            foreach ( $tuanData as $index => $tuan ) {
                $tuanData[$index]['count'] = $this -> _api -> getOrderTuanGoodsCount( $tuan['tuan_id'] );
                $tuanData[$index]['status'] = $this -> _api -> getTuanStatus( $tuanData[$index] );
                $tuanData[$index]['start_time'] = date( 'Y-m-d', $tuanData[$index]['start_time'] );
                $end_time = $tuanData[$index]['end_time'];
                $tuanData[$index]['end_time'] = date( 'Y-m-d', $tuanData[$index]['end_time'] );
                $tuanData[$index]['end_time_2'] = date( 'm/d/Y H:i:s', $end_time );
				$tuanData[$index]['discount'] = round(   $tuan['price'] /$tuan['market_price']* 10, 1 );
                $tuanData[$index] = $this -> _api -> getGoodsImg($tuanData[$index]);
            }
        }

        $this -> view -> datas = $tuanData;
        $this -> view -> type = $type;
        $pageNav = new Custom_Model_PageNav($total, 8);
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
     }
      /*
     *
     * 下期团购
     * */
     public function nextAction(){

        $search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
        $page = $search['page'] ? (int)$search['page'] : 1;
        $currentTime = time();
            $where = "t1.start_time > $currentTime and t1.end_time > $currentTime and t1.status = 0";
            $datas = $this -> _api -> get( $where, 't1.*,t2.img1,t2.goods_id as gid,t3.market_price,t3.goods_img', 'start_time DESC ' , $page, 8 );
        $total = $datas['total'];
        $tuanData = $datas['list'];
        if ( $total > 0 ) {
            foreach ( $tuanData as $index => $tuan ) {
                $tuanData[$index]['count'] = $this -> _api -> getOrderTuanGoodsCount( $tuan['tuan_id'] );
                $tuanData[$index]['status'] = $this -> _api -> getTuanStatus( $tuanData[$index] );
                $tuanData[$index]['start_time'] = date( 'Y-m-d', $tuanData[$index]['start_time'] );
                $end_time = $tuanData[$index]['end_time'];
                $tuanData[$index]['end_time'] = $end_time-time();;
                $tuanData[$index]['end_time_2'] = date( 'm/d/Y H:i:s', $end_time );
                $tuanData[$index]['discount'] = round(   $tuan['price'] /$tuan['market_price']* 10, 1 );
                $tuanData[$index] = $this -> _api -> getGoodsImg($tuanData[$index]);
            }
        }

        $this -> view -> datas = $tuanData;
        $pageNav = new Custom_Model_PageNav($total, 8);
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
		 $this -> view -> cur_place = 'help';
     }
     
     /*
      *
      * 团购详情
      *
      */
	public function detailAction(){
		
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
        $search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
        $id = $search['id'] ? $search['id'] : 0;
        $this -> _api -> setTuanCookie( $id );

        $data = $this -> _api -> getTuanById( $id );

        if ( !$data )   {
            Custom_Model_Message::showAlert('找不到指定的团购信息！', false, '/tuan');
            exit;
        }

        $data['count'] = $this -> _api -> getOrderTuanGoodsCount( $id );
        $data['status'] = $this -> _api -> getTuanStatus( $data );
        $data['start_time'] = date( 'Y-m-d H:i:s', $data['start_time'] );
        $end_time = $data['end_time'];
        $data['end_time'] =  $end_time - time();
        $data['end_time_2'] = date( 'm/d/Y H:i:s', $end_time );
        $data['discount'] = $data['market_price'] == 0 ? 0 : round( $data['price'] / $data['market_price'] * 10, 1 );
        $data = $this -> _api -> getGoodsImg($data, false);
        $this -> _msg = new Shop_Models_API_Msg();
        $buylog = $this -> _msg -> getBuyLog($data['tuan_goods_id'], 10);
		$this -> view -> buylog = $buylog;
		//团购热荐
		$gooslist = $this ->_api_goods->getTag(" tag_id = 6");
		$this->view->gooslist = $gooslist['details'];
		//今日团购
		 $currentTime = time();
		 $where = "t1.start_time <= $currentTime and t1.end_time > $currentTime and t1.status = 0";
         $datatuan = $this -> _api -> get( $where, 't1.*,t2.img1,t3.market_price,t3.goods_img', 'start_time DESC' );
         $this->view->todTuanlist = $datatuan['list'];
		if ( time() <= $end_time ) {
		    $this -> view -> cur_place = 'today';
		}
		else    $this -> view -> cur_place = 'old';

        $this -> view -> page_title = $data['title']."   垦丰电商 -专业的种子商城 ";
        $this -> view -> vo = $data;
        
	}
	/**
     * 团购详情页
     *
     * @return   void
     */
    public function viewAction()
    {
        $search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
        $search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
        $id = $search['id'] ? $search['id'] : 0;

        $this -> _api -> setTuanCookie( $id );

        $data = $this -> _api -> getTuanById( $id );
        if ( !$data )   {
            Custom_Model_Message::showAlert('找不到指定的团购信息！', false, '/turn');
            exit;
        }

        $data['count'] = $this -> _api -> getOrderTuanGoodsCount( $id );
        $data['status'] = $this -> _api -> getTuanStatus( $data );
        $data['start_time'] = date( 'Y-m-d H:i:s', $data['start_time'] );
        $end_time = $data['end_time'];
        $data['end_time'] = date( 'Y-m-d H:i:s', $end_time );
        $data['end_time_2'] = date( 'm/d/Y H:i:s', $end_time );
        $data['discount'] = round( $data['price'] / $data['market_price'] * 10, 1 );
        $data = $this -> _api -> getGoodsImg($data, false);

        $this -> _msg = new Shop_Models_API_Msg();
        $buylog = $this -> _msg -> getBuyLog($data['tuan_goods_id'], 10);
		$this -> view -> buylog = $buylog;

		if ( time() <= $end_time ) {
		    $this -> view -> cur_place = 'today';
		}
		else    $this -> view -> cur_place = 'old';

        $this -> view -> page_title = $data['title']."   垦丰电商 -专业的种子商城 ";
        $this -> view -> data = $data;
    }

    /**
     * 常见问题
     *
     * @return   void
     */
    public function helpAction()
    {
		$gooslist = $this ->_api_goods->getTag(" tag_id = 5");
		$this->view->gooslist = $gooslist['details'];
        $this -> view -> cur_place = 'help';
    }

    /**
     * 团800 API
     *
     * @return   void
     */
    public function tuan800Action()
    {
        header("Content-type: text/xml");
        echo $this -> _api -> createTuan800File();

        exit;
    }


    /**
     * QQ彩贝收录文件
     *
     * @return   void
     */
    public function qqcaibeiAction()
    {
        header("Content-type: text/xml");
        echo $this -> _api -> createQqcaibeiFile();
        exit;
    }
	
	public function qg411Action(){
		header('location:/');
		$this ->view->js_more=",common.js";
		
		$day_div = array('04','05','06','07','08','09','10','11');
		
		$this->view->day_div = $day_div;
		Zend_Controller_Front::getInstance() -> registerPlugin(new Custom_Controller_Plugin_Layout()); //卸载头部 尾部
		
		$ctime = mktime();
		$start_time = strtotime('2013-11-04 9:00:00');
		$end_time = strtotime('2013-11-11 20:00:00');
		
		$totime = 0;
		$cdate = date('Y-m-d',$ctime);
		$ndate = date('Y-m-d',$ctime+(24*3600));

		$t = array(9,15,20);		
		$ch = intval(date('H',$ctime));
		$arr_t = array();
		foreach ($t as $k => $v) {
			if(($v-$ch)<0) continue;
			$arr_t[] = $v;
		}
		
		asort($arr_t);
		
		if($ctime>=$start_time && $ctime<=$end_time){
			
			
			if(!empty($arr_t)){
				$totime = strtotime($cdate.' '.$arr_t[0].'.00:00')-$ctime;
			}else{
				$totime = strtotime($ndate.' 9:00:00')-$ctime;
			}
		
		}elseif($ctime<$start_time){
			$totime = ($start_time-$ctime);
		}
		
		$t = array_flip($day_div);
		$cday = date('d',$ctime);
		$this->view->cday_index = $t[$cday];
		
		$ctime_index = 1;
		if($ch>=15 && $ch<20){
			$ctime_index = 2;
		}elseif($ch>=20){
			$ctime_index = 3;
		}
		$this->view->ctime_index = $ctime_index;
				
		$this->view->totime = $totime;
		
	}

	public function ajaxQg411ProductAction(){

		$cday = $this->_request->getParam('cday',4);
		$this->view->cday = '0'.intval($cday);
		$begin_time = strtotime('2013-11-'.$cday.' 00:00:00');
		$end_time = strtotime('2013-11-'.(intval($cday)+1).' 00:00:00');
		$day_div = array('04','05','06','07','08','09','10','11');
		$hour_div = array('09:00','15:00','20:00');
		
		$this->view->day_div = $day_div;
		
		$ctime = mktime();

		$tbl = 'shop_tuan as t|t.tuan_id,t.title,t.start_time,t.end_time,t.goods_id as tuan_goods_id,t.price,t.max_count,t.count_limit,t.alt_count,t.status';
		
		$links = array();
		$links['shop_tuan_goods as tg'] = 'tg.id=t.goods_id|tg.goods_id';
		$links['shop_goods as g'] = 'g.goods_id=tg.goods_id|g.product_id';
		$links['shop_product as p'] = 'p.product_id=g.product_id|p.brand_id';
		$links['shop_brand as b'] = 'b.brand_id=p.brand_id|b.as_name as brand_as_name';
		
		$list = $this->_api->getAllWithLink($tbl,$links,array('start_time|gt'=>$begin_time,'start_time|lt'=>$end_time),0,'start_time asc');
				
		$list_tuan = array();
		$arr_goods_id = array();
		$arr_tuan_goods_id = array();
		foreach ($list as $k => $v) {
			
			$day = date('d',$v['start_time']);
			if(!in_array($day, $day_div)) continue;
			
			$hour = date('H:i',$v['start_time']);
			if(!in_array($hour, $hour_div)) continue;
			
			//$list_tuan[date('m-d',$v['start_time'])][date('H:i',$v['start_time'])][] = $v;
			$list_tuan[] = $v;
			
			$arr_tuan_goods_id[] = $v['tuan_goods_id'];
			$arr_goods_id[] = $v['goods_id'];
			
		}
		
		$list_goods = $this->_api->getAll('shop_goods',array('goods_id|in'=>$arr_goods_id),'goods_id,goods_name,price,market_price,goods_img');		
		$list_tuan_goods = $this->_api->getAll('shop_tuan_goods',array('id|in'=>$arr_tuan_goods_id));		
					
		$k_list_goods = Custom_Model_Tools::list_fkey($list_goods, 'goods_id');
		$k_list_tuan_goods = Custom_Model_Tools::list_fkey($list_tuan_goods, 'id');
		
		$conf_status_bg = array('not_started'=>'btn_state02.jpg','on_going'=>'btn_state03.jpg','completed'=>'btn_state01.jpg');
		
		foreach ($list_tuan as $k => $v) {
			$list_tuan[$k]['goods'] = $k_list_goods[$v['goods_id']];
			$list_tuan[$k]['tuan_goods'] = $k_list_tuan_goods[$v['tuan_goods_id']];
			$status = 'not_started';
			if($v['start_time']<=$ctime){
				$status = 'on_going';					
				if($v['alt_count']>$v['max_count']){
					$status = 'completed';
				}
			}
			
			$url = '/b-'.$v['brand_as_name'].'/detail'.$v['goods_id'].'.html';
			if($status == 'on_going'){
				$url = '/tuan/detail'.$v['tuan_id'].'.html';
			}

			$list_tuan[$k]['status_bg'] = $conf_status_bg[$status];
			$list_tuan[$k]['url'] = $url;
			
		}
		
		
		$this->view->list_tuan = $list_tuan;

	}

	

}