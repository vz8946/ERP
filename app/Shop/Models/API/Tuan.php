<?php
 class Shop_Models_API_Tuan extends Custom_Model_Dbadv
 {
 	/**
     * 团购DB
     *
     * @var Shop_Models_API_Tuan
     */
	private $_db = null;

	private $_cookie_name = 'tuan';

	private $_goodsAPI = null;

 	/**
     * 对象初始化
     *
     * @return void
     */
    public function __construct()
    {
        $this -> _db = new Shop_Models_DB_Tuan();
        $this -> _session = new Zend_Session_Namespace($this -> _userCertificationName);
        $this -> user = Shop_Models_API_Auth :: getInstance() -> getAuth();
		parent::__construct();
		
    }

	/**
     * 获取团购数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['title'] && $wheresql .= "t1.title like '%{$where['title']}%' and ";
		    $where['goods_title'] && $wheresql .= "t2.title like '%{$where['goods_title']}%' and ";
		    $where['goods_name'] && $wheresql .= "t3.goods_name like '%{$where['goods_name']}%' and ";
		    $where['status'] !== null && $wheresql .= "t1.status = '{$where['status']}' and ";
		    if ( $where['fromdate'] ) {
		        $wheresql .= "t1.start_time >= ".strtotime($where['fromdate'])." and ";
		    }
		    if ( $where['todate'] ) {
		        $wheresql .= "t1.end_time <= ".strtotime($where['todate'].' 23:59:59')." and ";
		    }
		    $wheresql .= '1';
		}
		
		else    $wheresql = $where;
		return $this -> _db -> fetch($wheresql, $fields, $orderBy, $page, $pageSize);
	}

	/**
     * 获取团购数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getTuan2Index($num)
	{
		$list = $this -> _db ->getTuan2Index($num);
		$len = count($list);
		for($i=0;$i<$len;$i++){
			if($list[$i]['price'] != 0){
				$list[$i]['discount'] =  round($list[$i]['market_price'] /$list[$i]['price']* 10, 1 );
			}
		}
		return $list;
	}

	/**
     * 按照ID查询
     *
     * @param    string    $id
     * @return   array
     */
	public function getTuanById($id = null)
	{
		return $this -> _db -> getTuanById($id);
	}

    /**
     * 获得团购状态
     *
     * @param    array      $data
     * @return   string
     */
	public function getTuanStatus($data)
	{
	    $currentTime = time();
	    if ( $data['start_time'] > $currentTime )     return 0; //未开始
	    if ( $data['end_time'] < $currentTime )       return 2; //已结束

	    if ( $data['max_count'] ) {
	        if ( !isset($data['count']) ) {
	            $count = $this -> getOrderTuanGoodsCount( $data['tuan_id'] );
	        }
	        if ( $count >= $data['max_count'] ) {
	            return 3;   //已售完
	        }
	    }
	    return 1;   //进行中
	}

	/**
     * 获得订单团购商品数量
     *
     * @param    int        $tuanID
     * @return   int
     */
	public function getOrderTuanGoodsCount($tuanID)
	{
	    $data = $this -> _db -> getOrderTuanGoodsByTuanID($tuanID, 'number');
	    if ( $data ) {
	        $number = 0;
	        $count = count($data);

	        for ( $i = 0; $i < $count; $i++ ) {
	            $number += $data[$i]['number'];
	        }
	        return $number;
	    }
	    else    return 0;
	}

    /**
     * 在cookie中设置tuanID
     *
     * @param    int        $tuanID
     * @return   void
     */
	public function setTuanCookie($tuanID)
	{
	    $tuan = $_COOKIE[$this->_cookie_name];
	    if ( $tuan ) {
	        $id_arr = explode( '/', $tuan );
	        if ( !in_array($tuanID, $id_arr) ) {
	            $id_arr[] = $tuanID;
	        }
	    }
	    else    $id_arr[] = $tuanID;

	    setcookie( $this->_cookie_name, implode('/', $id_arr), time() + 3600 * 24, '/' );
	}

	/**
     * 获得cookie中所有团购ID
     *
     * @return   array
     */
	public function getAllTuanCookie()
	{
	    $tuan = $_COOKIE[$this->_cookie_name];
	    if ( !$tuan )   return false;
	    return explode( '/', $tuan );
	}

	/**
     * 处理商品详情(暂时不用)
     *
     * @param    array        $datas
     * @return   array
     */
	public function responseToView($datas)
	{
	    $tuanIDArr = $this -> getAllTuanCookie();
	    if ( !$tuanIDArr )  return $datas;
	    $tuanData = $this -> filterTuan($tuanIDArr);
	    if ( !$tuanData )   return $datas;
	    foreach ( $tuanData as $tuan ) {
	        foreach ($datas as $key => $data) {
	            if ( $data['goods_id'] != $tuan['tuan_goods_id'] ) {
	                continue;
	            }
	            if ( $data['price'] <= $tuan['price'] ) {
	                continue;
	            }
	            $datas[$key]['org_price'] = $datas[$key]['org_price'] ? $datas[$key]['org_price'] : $data['price'];
    		    $datas[$key]['offers_type'] = 'tuan';
    		    $datas[$key]['price'] = $tuan['price'];
	        }
	    }
	    return $datas;
	}

	/**
     * 处理购物车
     *
     * @param    array        $datas
     * @return   array
     */
	public function responseToCart($datas)
	{
	    if (!$datas['data'] )   return $datas;
	    $tuanIDArr = $this -> getAllTuanCookie();
	    if ( !$tuanIDArr )  return $datas;
	    $tuanData = $this -> filterTuan($tuanIDArr);
	    if ( !$tuanData )   return $datas;
	    foreach ( $tuanData as $tuan ) {
	        $offers = '';
	        foreach ($datas['data'] as $key => $data) {
	            if ( $data['goods_id'] != $tuan['tuan_goods_id'] ) {
	                continue;
	            }
	            if ( $data['price'] <= $tuan['price'] ) {
	                continue;
	            }
	            $tuanNumber = $data['number'];
	            $commonNumber = 0;
	            if ( $tuan['max_count'] ) {
	                $count = $this -> getOrderTuanGoodsCount( $tuan['tuan_id'] );
	                if ( $count >= $tuan['max_count'] ) {
	                    continue;
	                }
	                if ( ($count + $data['number']) > $tuan['max_count'] ) {
	                    $tuanNumber = $tuan['max_count'] - $count;
	                    $commonNumber = $data['number'] - $tuanNumber;
	                }
	            }
	            if ( $tuan['count_limit'] ) {
	                if ( $tuanNumber > $tuan['count_limit'] ) {
	                    $tuanNumber = $tuan['count_limit'];
	                    $commonNumber = $data['number'] - $tuanNumber;
	                }
	            }
	            $datas['data'][$key]['number'] = $commonNumber;
	            $datas['data'][$key]['amount'] = $datas['data'][$key]['price'] * $datas['data'][$key]['number'];
	            $datas['data'][$key]['suffix'] = 1;
	            $tuan_data = $datas['data'][$key];
        	    $tuan_data['number'] = $tuanNumber;
        	    $tuan_data['org_price'] = $tuan_data['org_price'] ? $tuan_data['org_price'] : $tuan_data['price'];
        	    $tuan_data['price'] = $tuan['price'];
        	    $tuan_data['amount'] = $tuan_data['price'] * $tuanNumber;
        	    $tuan_data['member_discount'] = round($tuan_data['price'] / $tuan_data['org_price'] * 10, 1);
        	    $tuan_data['price_before_discount'] = $tuan_data['org_price'];
        	    $tuan_data['tuan_id'] = $tuan['tuan_id'];
    		    $tuan_data['suffix'] = 0;
    		    $tuan_datas[] = $tuan_data;
	            $offerProduct['offers_name'] = $tuan['title'];
    		    $offerProduct['offers_type'] = 'tuan';
    		    $offerProduct['price'] = $tuan_data['price'] - $tuan_data['org_price'];
    		    $offerProduct['number'] = $tuanNumber;
	            $offerProduct['parent_pid'] = $datas['data'][$key]['product_id'];
	            $offerProduct['freight'] = $tuan['freight'];
	            $offers[] = $offerProduct;
	            $datas['amount'] -= ($datas['data'][$key]['price'] - $tuan_data['price']) * $tuanNumber;
	            $datas['goods_amount'] -= ($datas['data'][$key]['price'] - $tuan_data['price']) * $tuanNumber;
	            if ( $datas['data'][$key]['number'] == 0 ) {
	                unset( $datas['data'][$key] );
	            }
	        }
	        $offers && $datas['offers']['tuan_'.$tuan['tuan_id']] = $offers;
	    }
	    if ( $tuan_datas ) {
    	    $datas['data'] = array_merge($datas['data'], $tuan_datas);
    	}
	    return $datas;
	}

	/**
     * 更新cookie团购并返回团购信息
     *
     * @param    array        $tuanIDArr
     * @return   array
     */
	private function filterTuan($tuanIDArr)
	{
	    $currentTime = time();
	    $where = "t1.start_time <= $currentTime and t1.end_time > $currentTime and t1.status = 0 and t1.tuan_id in (".implode(',', $tuanIDArr).")";
	    $datas = $this -> _db -> fetch($where, 't1.*,t2.goods_id as tuan_goods_id');
	    for ( $i = 0; $i < count($tuanIDArr); $i++ ) {
	        $tuanIDMap[$tuanIDArr[$i]] = 0;
	    }
	    if ( $datas['total'] > 0 ) {
	        foreach ( $datas['list'] as $data ) {
	            $tuanIDMap[$data['tuan_id']] = 1;
	        }
	    }
	    $newIDArr = array();
	    $id_arr = $this -> getAllTuanCookie();
	    for ( $i = 0; $i < count($id_arr); $i++ ) {
	        if ( in_array($id_arr[$i], $tuanIDArr) && !$tuanIDMap[$id_arr[$i]] ) {
	            continue;
	        }
	        $newIDArr[] = $id_arr[$i];
	    }
	    setcookie( $this->_cookie_name, implode('/', $newIDArr), time() + 3600 * 24, '/' );
	    return $datas['list'];
	}

	/**
     * 获得团购商品图片
     *
     * @param    array        $data
     * @return   array
     */
	function getGoodsImg($data, $justOne = true)
	{
	    $data['img'] = array();
	    if ( $data['img1'] )    $data['main_img'] = $data['img1'];
	    else                    $data['main_img'] = $data['goods_img'];

        if ( $justOne ) return $data;

        if ( $data['img2'] && $data['img3'] && $data['img4'] && $data['img5'] ) {
            $data['img'][] = $data['img2'];
            $data['img'][] = $data['img3'];
            $data['img'][] = $data['img4'];
            $data['img'][] = $data['img5'];
            return $data;
        }
        if ( !$this -> _goodsAPI ) {
            $this -> _goodsAPI = new Shop_Models_API_Goods();
        }
        $imgData = $this ->_goodsAPI -> getImg("product_id = {$data['product_id']} and img_type = 3");
        if ( !$imgData )    return $data;
        $index = 0;
        for ( $i = 2; $i <= 5; $i++ ) {
            if ( $data['img'.$i] ) {
                $data['img'][] = $data['img'.$i];
                continue;
            }
            if ( count($imgData) <= $index ) continue;

            $data['img'][] = $imgData[$index]['img_url'];
            $index++;
        }
	    return $data;
	}

	/**
     * 生成团800配置文件
     *
     * @param    array        $data
     * @return   array
     */
	function createTuan800File()
	{
	    $datas = $this -> get(array('status' => 0));
	    $content = '<?xml version="1.0" encoding="utf-8" ?>
	                  <urlset>'.chr(13).chr(10);
        $index = 0;
        foreach ( $datas['list'] as $data ) {
            $data = $this -> getGoodsImg($data, true);
            $count = $this -> getOrderTuanGoodsCount($data['tuan_id']) + $data['alt_count'];
            $content .= "<url>
                         <loc>http://www.1jiankang.com/tuan/view/id/{$data['tuan_id']}</loc>
                         <data>
                         <display>
                         <website>垦丰</website>
                         <identifier>{$data['tuan_id']}</identifier>
                         <siteurl>http://www.1jiankang.com/tuan</siteurl>
                         <city>全国</city>
                         <title>{$data['title']}(全国范围)</title>
                         <shortTitle>{$data['title']}</shortTitle>
                         <image>".Zend_Registry::get('config') -> view -> imgBaseUrl.'/'."{$data['main_img']}</image>
                         <tag>美食,邮购</tag>
                         <startTime>".date('Y-m-d H:i:s', $data['start_time'])."</startTime>
                         <endTime>".date('Y-m-d H:i:s', $data['end_time'])."</endTime>
                         <value>{$data['market_price']}</value>
                         <price>{$data['price']}</price>
                         <rebate>".round($data['price'] / $data['market_price']*10, 1)."</rebate>
                         <bought>{$count}</bought>".chr(13).chr(10);
            if ( $data['max_count'] ) {
                $content .= "<maxQuota>{$data['max_count']}</maxQuota>".chr(13).chr(10);
            }
            $content .= "<post>yes</post>
                         <soldOut>no</soldOut>
                         <priority>{$index}</priority>
                         <tip><![CDATA[{$data['description']}]]></tip>
                         </display>
                         <shops>
                         <shop>
                         <name>垦丰</name>
                         <tel>400-603-3883</tel>
                         </shop>
                         </shops>
                         </data>
                         </url>".chr(13).chr(10);
            $index++;
        }
	    $content .= '</urlset>';
	    return $content;
	}

	/**
     * 生成QQ彩贝收录文件
     *
     * @param    array        $data
     * @return   array
     */
	function createQqcaibeiFile()
	{
	    $datas = $this -> get(array('status' => 0));
	    $content = '<?xml version="1.0" encoding="utf-8" ?>
	                  <urlset>'.chr(13).chr(10);
        $index = 0;
        foreach ( $datas['list'] as $data ) {
            $data = $this -> getGoodsImg($data, true);
            $count = $this -> getOrderTuanGoodsCount($data['tuan_id']) + $data['alt_count'];
            $content .= "<url>
                         <loc>http://www.1jiankang.com/tuan/view/id/{$data['tuan_id']}</loc>
                         <data>
                         <display>
                         <website>垦丰</website>
                         <identifier>{$data['tuan_id']}</identifier>
                         <siteurl>http://www.1jiankang.com/tuan</siteurl>
                         <city>全国</city>
                         <title>{$data['title']}(全国范围)</title>
                         <shortTitle>{$data['title']}</shortTitle>
                         <image>".Zend_Registry::get('config') -> view -> imgBaseUrl.'/'."{$data['main_img']}</image>
                         <tag>5</tag>
                         <sub_tag>4</sub_tag>
                         <priority>{$index}</priority>
                         <startTime>".date('Y-m-d H:i:s', $data['start_time'])."</startTime>
                         <endTime>".date('Y-m-d H:i:s', $data['end_time'])."</endTime>
                         <value>{$data['market_price']}</value>
                         <price>{$data['price']}</price>
                         <rebate>".round($data['price'] / $data['market_price']*10, 1)."</rebate>
                         <bought>{$count}</bought>".chr(13).chr(10);
            if ( $data['max_count'] ) {
                $content .= "<maxQuota>{$data['max_count']}</maxQuota>".chr(13).chr(10);
            }
            $content .= "<soldOut>0</soldOut>
                         </display>
                         </data>
                         </url>".chr(13).chr(10);
            $index++;
        }
	    $content .= '</urlset>';
	    return $content;
	}
	
 }