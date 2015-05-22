<?php

class Admin_Models_API_Sitemap
{
	/**
     *  DB
     * 
     * @var Admin_Models_DB_Sitemap
     */
	private $_db = null;
	
	/**
     * 对象初始化
     * 
     * @return void
     */
	public function __construct()
    {
        $this -> _db = new Admin_Models_DB_Sitemap();
	}
	
	/**
     * 取得商品分类数据
     *
     * @param    array   $search
     * @param    int     $page
     * @param    int     $pageSize
     * @return   array
     */
	public function getGoodsCatsList()
	{
		return $this -> _db -> getGoodsCatsList();
	}
	
	/**
     * 取得商品数据
     *
     * @param    array   $search
     * @param    int     $page
     * @param    int     $pageSize
     * @return   array
     */
	public function getGoodsList()
	{
		return $this -> _db -> getGoodsList();
	}
	
	/**
     * 取得文章数据
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function getArticleList()
	{
		return $this -> _db -> getArticleList();
	}
	
	public function createSitemap($sr){
		
		
	    $xml = new Custom_Config_Xml();
	    $config=$xml->getConfig();
	    $base_url = $config -> masterdomain;
		
    	$apiGoods = new Shop_Models_API_Goods();
    	$apiBrand = new Admin_Models_API_Brand();
    	$apiNews = new Admin_Models_API_News();
    	
    	$tree_nav_cat = $apiGoods -> getCatNavTree();

    	$list_brand = $apiBrand->getAll();
    	$list_goods = $apiGoods->getGoodsAll();
		
    	$list_news = $apiNews->getNewsAll();
    	$list_news_article = $apiNews->getNewsArticleAll();
		$list_news_cat = $apiNews->getAll('shop_seo_cat',array('is_del'=>'N'));
		$list_news_tag = $apiNews->getAll('shop_seo_tag',array('is_del'=>'N'));
		
    	$list_group_goods = $apiGoods->getGroupGoodsAll();

    	$list_news_artilce = $apiNews->getNewsArticleAll(); //新版资讯
		
    	$cdate = date('Y-m-d',time());
    
    	$content_header = '<?xml version="1.0" encoding="utf-8"?>'."\n".'<urlset>'."\n";
    	$content_end = '</urlset>';
		
    	$sitemap_html_header = $this->get_sitemap_html_header();
    	$sitemap_html_footer = $this->get_sitemap_html_footer();
    
    	$items = array();
    	$txt_items = array();
    	$html_items = array();
    
    	//零散页面
    	$arr_custom = array('垦丰首页'=>'','垦丰人气爆款页'=>'baokuan.html','垦丰专题页'=>'topics.html','垦丰资讯首页'=>'news',
    		'垦丰精品页'=>'jpy.html','垦丰品牌页'=>'brand.html','垦丰聚焦页'=>'zp.html','垦丰正品保障页'=>'zpbz.html',
    		'垦丰商品推荐页'=>'prom.html','垦丰组合商品页'=>'group-goods');
		
    	foreach ($arr_custom as $k => $v) {
    		$item = '';
    		$item .= "\t".'<url>'."\n";
    		$item .= "\t"."\t".'<loc>'.$base_url.'/'.$v.'</loc>'."\n";
    		$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    		$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    		$item .= "\t".'</url>'."\n";
    		$items[] = $item;
    		$txt_items[] = ''.$base_url.'/'.$v;
    		$html_items[] = '<a href="'.$base_url.'/'.$v.'">'.$k.'</a>';
    	}
    
    	//所有类目
    	foreach ($tree_nav_cat as $k=>$v){
    		 
    		$item = '';
    		$item .= "\t".'<url>'."\n";
    		$item .= "\t"."\t".'<loc>'.$base_url.'/gallery-'.$v['cat_id'].'.html</loc>'."\n";
    		$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    		$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    		$item .= "\t".'</url>'."\n";
    		$items[] = $item;
    		$txt_items[] = ''.$base_url.'/gallery-'.$v['cat_id'].'.html';
    		$html_items[] = '<a href="'.$base_url.'/gallery-'.$v['cat_id'].'.html">'.$v['cat_name'].'</a>';
    			
    		if(!empty($v['sub'])){
    			foreach ($v['sub'] as $kk=>$vv){
    				$item = '';
    				$item .= "\t".'<url>'."\n";
    				$item .= "\t"."\t".'<loc>'.$base_url.'/gallery-'.$vv['cat_id'].'.html</loc>'."\n";
    				$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    				$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    				$item .= "\t".'</url>'."\n";
    				$items[] = $item;
    				$txt_items[] = ''.$base_url.'/gallery-'.$vv['cat_id'].'.html';
    				$html_items[] = '<a href="'.$base_url.'/gallery-'.$vv['cat_id'].'.html">'.$vv['cat_name'].'</a>';
    			}
    		}
    	}
    
    	//所有品牌
    	foreach ($list_brand as $k=>$v){
    		$item = '';
    		$item .= "\t".'<url>'."\n";
    		$item .= "\t"."\t".'<loc>'.$base_url.'/b-'.$v['as_name'].'/</loc>'."\n";
    		$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    		$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    		$item .= "\t".'</url>'."\n";
    		$items[] = $item;
    		$txt_items[] = ''.$base_url.'/b-'.$v['as_name'].'/';
    		$html_items[] = '<a href="'.$base_url.'/b-'.$v['as_name'].'/">'.$v['brand_name'].'</a>';
    	}
		
    	//全部商品
    	foreach ($list_goods as $k=>$v){
    		$item = '';
    		$v['as_name'] = empty($v['as_name']) ? 'jiankang' : $v['as_name'];
    		$item .= "\t".'<url>'."\n";
    		$item .= "\t"."\t".'<loc>'.$base_url.'/b-'.$v['as_name'].'/detail'.$v['goods_id'].'.html</loc>'."\n";
    		$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    		$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    		$item .= "\t".'</url>'."\n";
    		$items[] = $item;
    		$txt_items[] = ''.$base_url.'/b-'.$v['as_name'].'/detail'.$v['goods_id'].'.html';
    		$html_items[] = '<a href="'.$base_url.'/b-'.$v['as_name'].'/detail'.$v['goods_id'].'.html">'.$v['goods_name'].'</a>';
    	}
    	
    	//组合商品
    	foreach ($list_group_goods as $k=>$v){
    		$item = '';
    		$v['as_name'] = empty($v['as_name']) ? '1jiankang' : $v['as_name'];
    		$item .= "\t".'<url>'."\n";
    		$item .= "\t"."\t".'<loc>'.$base_url.'/groupgoods-'.$v['group_id'].'.html</loc>'."\n";
    		$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    		$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    		$item .= "\t".'</url>'."\n";
    		$items[] = $item;
    		$txt_items[] = ''.$base_url.'/groupgoods-'.$v['group_id'].'.html';
    		$html_items[] = '<a href="'.$base_url.'/groupgoods-'.$v['group_id'].'.html">'.$v['group_goods_name'].'</a>';
    	}
		
    	//咨询频道
    	foreach ($list_news_cat as $k=>$v){
    		$item = '';
    		$item .= "\t".'<url>'."\n";
    		$item .= "\t"."\t".'<loc>'.$base_url.'/news-'.$v['as_name'].'</loc>'."\n";
    		$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    		$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    		$item .= "\t".'</url>'."\n";
    		$items[] = $item;
    		$txt_items[] = $base_url.'/news-'.$v['as_name'];
    		$html_items[] = '<a href="'.$base_url.'/news-'.$v['as_name'].'">'.$v['title'].'</a>';
    	}
    
    	//咨询标签
    	foreach ($list_news_tag as $k=>$v){
    		$item = '';
    		$item .= "\t".'<url>'."\n";
    		$item .= "\t"."\t".'<loc>'.$base_url.'/chanel-'.$v['name'].'</loc>'."\n";
    		$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    		$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    		$item .= "\t".'</url>'."\n";
    		$items[] = $item;
    		$txt_items[] = $base_url.'/chanel-'.$v['name'];
    		$html_items[] = '<a href="'.$base_url.'/chanel-'.$v['name'].'">'.$v['title'].'</a>';
    	}
    
    	//咨询信息
    	foreach ($list_news_article as $k=>$v){
    		$item = '';
    		$item .= "\t".'<url>'."\n";
    		$item .= "\t"."\t".'<loc>'.$base_url.'/news-'.$v['as_name'].'/detail-'.$v['article_id'].'.html</loc>'."\n";
    		$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    		$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    		$item .= "\t".'</url>'."\n";
    		$items[] = $item;
    		$txt_items[] = $base_url.'/news-'.$v['as_name'].'/detail-'.$v['article_id'].'.html';
    		$html_items[] = '<a href="'.$base_url.'/news-'.$v['as_name'].'/detail-'.$v['article_id'].'.html">'.$v['title'].'</a>';
    	}
    
    	//咨询信息
    	foreach ($list_news as $k=>$v){
    		$item = '';
    		$item .= "\t".'<url>'."\n";
    		$item .= "\t"."\t".'<loc>http://news.1jiankang.com/'.$v['asName'].'/news-'.$v['id'].'.html</loc>'."\n";
    		$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    		$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    		$item .= "\t".'</url>'."\n";
    		$items[] = $item;
    		$txt_items[] = 'http://news.1jiankang.com/'.$v['asName'].'/news-'.$v['id'].'.html';
    		$html_items[] = '<a href="http://news.1jiankang.com/'.$v['asName'].'/news-'.$v['id'].'.html">'.$v['title'].'</a>';
    	}
	
    	//帮助中心文章
    	$config  = new Shop_Models_API_Page();
    	$list_help_center = $config-> getListCat("parent_id =1");
    	foreach ($list_help_center as $k=>$v){
    		if(empty($v['list'])) continue;
    		foreach ($v['list'] as $kk => $vv) {
    			$item = '';
    			$item .= "\t".'<url>'."\n";
    			$item .= "\t"."\t".'<loc>'.$base_url.'/help/detail-'.$vv['article_id'].'.html</loc>'."\n";
    			$item .= "\t"."\t".'<lastmod>'.$cdate.'</lastmod>'."\n";
    			$item .= "\t"."\t".'<changefreq>daily</changefreq>'."\n";
    			$item .= "\t".'</url>'."\n";
    			$items[] = $item;
    			$txt_items[] = ''.$base_url.'/help/detail-'.$vv['article_id'].'.html';
    			$html_items[] = '<a href="'.$base_url.'/help/detail-'.$vv['article_id'].'.html">'.$vv['title'].'</a>';
    		}
    	}
    
    	$objFile = new Custom_Model_File();
    
    	//删除所有文件
    	Custom_Model_File::dir_delete($sr.'/www/sitemap/');
    	Custom_Model_File::makeDir($sr.'/www/sitemap/');
    
    	//生成xml
    	$div = 2000;
    	$i = 0;
    	while (true) {
    		$t = array_slice($items, ($i*$div), $div, FALSE);
			if(count($t) == 0) break;
    		if($i>0)
    		{
    		    $objFile->writefile($sr.'/www/sitemap/sitemap-'.$i.'.xml',$content_header.implode('', $t).$content_end);
    		    echo $sr.'/www/sitemap/sitemap-'.$i.'.xml'.'生成成功！<br/>';
    		}else{
    			$objFile->writefile($sr.'/www/sitemap/sitemap.xml',$content_header.implode('', $t).$content_end);
    			echo $sr.'/www/sitemap/sitemap.xml'.'生成成功！<br/>';
    		}    		
    		$i++;
    		if(count($t)<$div) break;
    	}
    
    	//生成txt
    	$objFile->writefile($sr.'/www/sitemap/sitemap.txt',implode("\n", $txt_items));
    	echo $sr.'/www/sitemap/sitemap.txt'.'生成成功！<br/>';
    
    	//生成html
    	$div = 200;
    	$i = 0;
    
    	$total_page = round((count($html_items))/$div);
    	while (true) {
    		$t = array_slice($html_items, ($i*$div), $div, FALSE);
			if(count($t) == 0) break;
    		$sitemap_html_pagenav = $this->get_sitemap_html_pagenav($i+1, $total_page-1);
    		if($i>0)
    		{
    		    $objFile->writefile($sr.'/www/sitemap/sitemap-'.($i+1).'.html',$sitemap_html_header.implode('<br/>', $t).$sitemap_html_pagenav.$sitemap_html_footer);
    		    echo $sr.'/www/sitemap/sitemap-'.($i+1).'.html'.'生成成功！<br/>';
    		}else{
    		  $objFile->writefile($sr.'/www/sitemap/sitemap.html',$sitemap_html_header.implode('<br/>', $t).$sitemap_html_pagenav.$sitemap_html_footer);
    		  $objFile->writefile($sr.'/www/sitemap/sitemap-1.html',$sitemap_html_header.implode('<br/>', $t).$sitemap_html_pagenav.$sitemap_html_footer);
    		  echo $sr.'/www/sitemap/sitemap.html'.'生成成功！<br/>';
    		  echo $sr.'/www/sitemap/sitemap-1.html'.'生成成功！<br/>';
    		}
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
    
    	$link_first = '<a href="'.$baseurl.'/sitemap-1.html">首页</a>&nbsp;&nbsp;';
    	$link_prev = '<a href="'.$baseurl.'/sitemap-'.$prev_pn.'.html">上一页</a>&nbsp;&nbsp;';
    	$link_next = '<a href="'.$baseurl.'/sitemap-'.$next_pn.'.html">下一页</a>&nbsp;&nbsp;';
    	$link_last = '<a href="'.$baseurl.'/sitemap-'.$total_page.'.html">尾页</a>&nbsp;&nbsp;';
    	$tip_total_page = '共'.$total_page.'页&nbsp;&nbsp;';
    	$slt_page = '<select onchange="window.location.href=this.value">';
    	for($i=1;$i<=$total_page;$i++){
    		$selected = $i == $cn ? ' selected="selected" ' : '';
    		$slt_page .= '<option '.$selected.' value="'.$baseurl.'/sitemap-'.$i.'.html">第'.$i.'页</option>';
    	}
    	$slt_page .= '</select>';
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
    
    
}