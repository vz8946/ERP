<?php

class HelpController extends Zend_Controller_Action
{
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
        $this -> _pageAPI = new Shop_Models_API_Page();
		$this->view->cur_position = 'help';
	}

	/**
     * 帮助中心
     *
     * @return void
     */
	public function indexAction()
	{
		$this -> view -> menu = $this -> _pageAPI -> getListCat("parent_id =1");
		$this->view->css_more = ',help.css'; 
        $this -> view -> page_title = '垦丰电商 - 帮助中心 ' . ($info['title'] ? ' - ' . $info['title'] : '');
		$this -> view -> page_keyword = "垦丰电商";
	    $this -> view -> page_description ="垦丰电商";
		$this -> view -> ur_here = '<div class="position wbox"><b><a title="垦丰商城" href="/">垦丰商城</a></b> &gt;&gt; <a title="帮助中心" href="/help/">帮助中心</a> &gt;&gt; </div>';
	}

	/**
     * 帮助中心列表
     *
     * @return void
     */
	public function listAction()
	{
		$this -> view -> menu = $this -> _pageAPI -> getListCat("parent_id =1");
		$id = (int)$this -> _request -> getParam('id', 1);
		$info = array_shift($this -> _pageAPI -> getInfo("article_id='$id'"));
		!$info && exit('error');
		$this->view->css_more = ',help.css';
        $this -> view -> page_title = '垦丰电商帮助中心-'.$info['title'].'-垦丰商城'; 
        $this -> view -> page_keyword = $info['title'].',垦丰电商'.$info['title'].',垦丰商城'.$info['title']; 
        $this -> view -> page_description = '垦丰商城'.$info['title'].',垦丰商城'.$info['title'].',垦丰电商-专业的种子商城，绝对保证种子品质!'; 
		$this -> view -> ur_here = '<div class="position wbox"><b><a title="垦丰商城" href="/">垦丰商城</a></b> &gt;&gt; <a title="帮助中心" href="/help/">帮助中心</a> &gt;&gt; '. $info['title'].' </div>';
		$this -> view -> info = $info;
		$this -> view -> article_id = $id;
	}

	/**
     * 信息
     *
     * @return void
     */
	public function infoAction()
	{
		$id = (int)$this -> _request -> getParam('id', 0);
		$info = array_shift($this -> _pageAPI -> getInfo(" is_view = 1 and article_id='$id'"));
		!$info && exit('error');
		$this->view->css_more = ',help.css';
		$this -> view -> info = $info;
        $this -> view -> page_title = '垦丰电商帮助中心 ' . ($info['title'] ? ' - ' . $info['title'] : ''.'垦丰电商 -专业的种子商城');
        $this -> view -> page_keyword = "垦丰电商帮助中心,垦丰帮助中心,帮助中心";
        $this -> view -> page_description = '垦丰电商 -专业的种子商城，绝对保证种子品质! ';
		$this -> view -> ur_here = " <a href='/'>首页</a> <code>&gt;</code> ".$info['cat_name']." <code>&gt;</code>". $info['title'] ;
	}


	/**
     * 配送区域
     *
     * @return void
     */
	public function logisticsAction()
	{
        $this -> view -> page_title = '配送区域查询';
        $this -> view -> page_keyword = "垦丰电商帮助中心,垦丰帮助中心,帮助中心";
        $this -> view -> page_description = '垦丰电商 -专业的种子商城，绝对保证种子品质! ';
	}

	/**
     * 返回JSON查询结果
     *
     * @return void
     */
    public function returnAction()
	{
        $cityId = intval($this -> _request -> getParam('cityId', 0));
        $regionId = intval($this -> _request -> getParam('regionId', 0));
        if ( $cityId && $regionId ) {
            require_once(Zend_Registry::get('systemRoot').'/data/logistics_area/areas.php');
            if ( isset($logisticsArea) && isset($logisticsArea[$cityId][$regionId])) {
                echo json_encode($logisticsArea[$cityId][$regionId]);
            }
        } else {
            echo json_encode(array());
        }
	    exit;
    }
	/**
     * 生成站点地图
     *
     * @return void
     */
    public function sitemapAction(){
    	
        $apiGoods = new Shop_Models_API_Goods();
        $apiBrand = new Admin_Models_API_Brand();
        $this->view->tree_cat = $tree_nav_cat = $apiGoods -> getCatNavTree();
        $this->view->css_more= ',moreclass.css';
    }

    public function createSitemapAction(){
    	
        $apiGoods = new Shop_Models_API_Goods();
        $apiBrand = new Admin_Models_API_Brand();
		
        $this->view->tree_cat = $tree_nav_cat = $apiGoods -> getCatNavTree();
        $this->view->css_more= ',moreclass.css';
        $list_brand = $apiBrand->getAll();
		$list_goods = $apiGoods->getGoodsAll();

		$list_group_goods = $apiGoods->getGroupGoodsAll();
		
        $cdate = date('Y-m-d',time());
        
        $content_header = '<?xml version="1.0" encoding="utf-8"?>'."\n".'<urlset>'."\n";
		$content_end = '</urlset>';
		$sitemap_html_header = $this->get_sitemap_html_header();
		$sitemap_html_footer = $this->get_sitemap_html_footer();
		
		$items = array();
		$txt_items = array();
		$html_items = array();
		
		//零散页面
		$arr_custom = array('','baokuan.html','topics.html','jpy.html','brand.html','zt.html','prom.html','group-goods');
		foreach ($arr_custom as $k => $v) {
			$item = '';
	        $item .= "\t".'<url>'."\n";
	        $item .= "\t"."\t".'<loc>http://www.1jiankang.com/'.$v.'</loc>'."\n";
	        $item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
	        $item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
	        $item .= "\t".'</url>'."\n";
			$items[] = $item;
			$txt_items[] = '#'.$v;
			$html_items[] = '<a href="#">垦丰</a>';
		}

		//所有类目
        foreach ($this->view->tree_cat as $k=>$v){
        	
            $item = '';
            $item .= "\t".'<url>'."\n";
            $item .= "\t"."\t".'<loc>http://jiankang/gallery-'.$v['cat_id'].'.html</loc>'."\n";
            $item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
            $item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
            $item .= "\t".'</url>'."\n";
            $items[] = $item;
			$txt_items[] = 'http://jiankang/gallery-'.$v['cat_id'].'.html';
			$html_items[] = '<a href="http://jiankang/gallery-'.$v['cat_id'].'.html">'.$v['cat_name'].'</a>';
			
            if(!empty($v['sub'])){
                foreach ($v['sub'] as $kk=>$vv){
                	$item = '';
                    $item .= "\t".'<url>'."\n";
                    $item .= "\t"."\t".'<loc>http://jiankang/gallery-'.$vv['cat_id'].'.html</loc>'."\n";
                    $item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
                    $item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
                    $item .= "\t".'</url>'."\n";
					$items[] = $item;
					$txt_items[] = 'http://jiankang/gallery-'.$vv['cat_id'].'.html';
					$html_items[] = '<a href="http://jiankang/gallery-'.$vv['cat_id'].'.html">'.$vv['cat_name'].'</a>';
					
                }
            }
        }
		
		//所有品牌
        foreach ($list_brand as $k=>$v){
        	$item = '';
            $item .= "\t".'<url>'."\n";
            $item .= "\t"."\t".'<loc>http://jiankang/b-'.$v['as_name'].'/</loc>'."\n";
            $item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
            $item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
            $item .= "\t".'</url>'."\n";
			$items[] = $item;
			$txt_items[] = 'http://jiankang/b-'.$v['as_name'].'/';
			$html_items[] = '<a href="http://jiankang/b-'.$v['as_name'].'/">'.$v['brand_name'].'</a>';
        }        
		
		//全部商品
        foreach ($list_goods as $k=>$v){
        	$item = '';
        	$v['as_name'] = empty($v['as_name']) ? '1jiankang' : $v['as_name'];
            $item .= "\t".'<url>'."\n";
            $item .= "\t"."\t".'<loc>http://jiankang/b-'.$v['as_name'].'/detail'.$v['goods_id'].'.html</loc>'."\n";
            $item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
            $item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
            $item .= "\t".'</url>'."\n";
			$items[] = $item;
			$txt_items[] = 'http://jiankang/b-'.$v['as_name'].'/detail'.$v['goods_id'].'.html';
			$html_items[] = '<a href="http://jiankang/b-'.$v['as_name'].'/detail'.$v['goods_id'].'.html">'.$v['goods_name'].'</a>';
        }        
		
		//组合商品
        foreach ($list_group_goods as $k=>$v){
        	$item = '';
        	$v['as_name'] = empty($v['as_name']) ? '1jiankang' : $v['as_name'];
            $item .= "\t".'<url>'."\n";
            $item .= "\t"."\t".'<loc>http://jiankang/groupgoods-'.$v['group_id'].'.html</loc>'."\n";
            $item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
            $item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
            $item .= "\t".'</url>'."\n";
			$items[] = $item;
			$txt_items[] = 'http://jiankang/groupgoods-'.$v['group_id'].'.html';
			$html_items[] = '<a href="http://jiankang/groupgoods-'.$v['group_id'].'.html">'.$v['group_goods_name'].'</a>';
        }        
		
		//帮助中心文章
		$list_help_center = $this -> _pageAPI -> getListCat("parent_id =1");
        foreach ($list_help_center as $k=>$v){
        	if(empty($v['list'])) continue;
			foreach ($v['list'] as $kk => $vv) {
				$item = '';
	            $item .= "\t".'<url>'."\n";
	            $item .= "\t"."\t".'<loc>http://jiankang/help/detail-'.$vv['article_id'].'.html</loc>'."\n";
	            $item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
	            $item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
	            $item .= "\t".'</url>'."\n";
				$items[] = $item;
				$txt_items[] = 'http://jiankang/help/detail-'.$vv['article_id'].'.html';
				$html_items[] = '<a href="http://jiankang/help/detail-'.$vv['article_id'].'.html">'.$vv['title'].'</a>';
			}			
        }        
		
        $objFile = new Custom_Model_File();
		
		//删除所有文件
		Custom_Model_File::dir_delete(SYSROOT.'/www/sitemap/');
		Custom_Model_File::makeDir(SYSROOT.'/www/sitemap/');
		
		//生成xml
		$div = 2000;
		$i = 0;
		while (true) {
			$t = array_slice($items, ($i*$div), $div, FALSE);
	        $objFile->writefile(SYSROOT.'/www/sitemap/sitemap'.$i.'.xml',$content_header.implode('', $t).$content_end);
			echo SYSROOT.'/www/sitemap/sitemap'.$i.'.xml'.'生成成功！<br/>';
			$i++;
			if(count($t)<$div) break;
		}
		
		//生成txt
        $objFile->writefile(SYSROOT.'/www/sitemap/sitemap.txt',implode("\n", $txt_items));
		echo SYSROOT.'/www/sitemap/sitemap.txt'.'生成成功！<br/>';
				
		//生成html
		$div = 200;
		$i = 0;
		
		$total_page = round((count($html_items))/$div);
		while (true) {
			$t = array_slice($html_items, ($i*$div), $div, FALSE);
			$sitemap_html_pagenav = $this->get_sitemap_html_pagenav($i+1, $total_page);
	        $objFile->writefile(SYSROOT.'/www/sitemap/sitemap-'.($i+1).'.html',$sitemap_html_header.implode('<br/>', $t).$sitemap_html_pagenav.$sitemap_html_footer);
			echo SYSROOT.'/www/sitemap/sitemap-'.($i+1).'.html'.'生成成功！<br/>';
			$i++;
			if(count($t)<$div) break;
		}
		
		exit;
    }

    private function get_sitemap_html_pagenav($cn,$total_page){

	    $xml = new Custom_Config_Xml();
	    $config=$xml->getConfig();
	    $baseurl = $config -> masterdomain;
		
		$prev_pn = ($cn-1) < 1 ? 1 : ($cn-1);
		$next_pn = ($cn+1) > $total_page ? $total_page : ($cn+1);
		
    	$link_first = '<a href="'.$baseurl.'/sitemap/sitemap-1.html">首页</a>&nbsp;&nbsp;';
    	$link_prev = '<a href="'.$baseurl.'/sitemap/sitemap-'.$prev_pn.'.html">上一页</a>&nbsp;&nbsp;';
    	$link_next = '<a href="'.$baseurl.'/sitemap/sitemap-'.$next_pn.'.html">下一页</a>&nbsp;&nbsp;';
    	$link_last = '<a href="'.$baseurl.'/sitemap/sitemap-'.$total_page.'.html">尾页</a>&nbsp;&nbsp;';
		$tip_total_page = '共'.$total_page.'页&nbsp;&nbsp;';
		$slt_page = '<select onchange="window.location.href=this.value">';
		for($i=1;$i<=$total_page;$i++){
			$selected = $i == $cn ? ' selected="selected" ' : '';
			$slt_page .= '<option '.$selected.' value="'.$baseurl.'/sitemap/sitemap-'.$i.'.html">第'.$i.'页</option>';
		}
		$slt_page .= '<select>';
    	$rs = '<div style="padding:20px 0px;">'.$link_first.$link_prev.$link_next.$link_last.$tip_total_page.$slt_page.'</div>';
		return $rs;
    }
    
	private function get_sitemap_html_header(){
		$rs = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
			<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<title>Insert title here</title>
			</head>
			<body>		
		';
		return $rs;
	}

	private function get_sitemap_html_footer(){
		$rs = '
			</body>
			</html>
		';
		return $rs;
	}


    public function sitemapBrandAction(){
        $apiBrand = new Admin_Models_API_Brand();
        $list_brand = $apiBrand->getAll();
        $list_char_brand = array();
        $conf_char_pos = array(
                'A'=>'-80',
                'B'=>'-80',
                'C'=>'-80',
                'D'=>'-80',
                'E'=>'-80',
                'F'=>'-80',
                'G'=>'-80',
                'H'=>'-100',
                'I'=>'-180',
                'J'=>'-180',
                'K'=>'-180',
                'L'=>'-210',
                'M'=>'-240',
                'N'=>'-280',
                'O'=>'-320',
                'P'=>'-360',
                'Q'=>'-400',
                'R'=>'-430',
                'S'=>'-460',
                'T'=>'-500',
                'U'=>'-540',
                'V'=>'-570',
                'W'=>'-600',
                'X'=>'-630',
                'Y'=>'-670',
                'Z'=>'-650'
        );
        foreach ($list_brand as $k=>$v){
            if(empty($v['char'])) continue;

            if(in_array($v['char'], array('A','B','C','D','E','F'))){
                $list_char_brand['A-F'][] = $v;
            }elseif(in_array($v['char'], array('G','H','I','J','K','L'))){
                $list_char_brand['G-L'][] = $v;
            }elseif(in_array($v['char'], array('M','N','O','P','Q','R'))){
                $list_char_brand['M-R'][] = $v;
            }elseif(in_array($v['char'], array('S','T','U','V','W','X','Y','Z'))){
                $list_char_brand['S-Z'][] = $v;
            }else{
                continue;
            }
        }
        ksort($list_char_brand);
        $this->view->list_brand = $list_char_brand;
        $this->view->css_more= ',moreclass.css';

    }
}