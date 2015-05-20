<?php
class IndexController extends Zend_Controller_Action
{
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
        
	}
		
	/**
     * 首页
     *
     * @return void
     */
	public function indexAction()
	{
		$this->view->is_index_page = true;		
        $this->view->css_more=',index.css,drt.css';
        $this -> view -> page_title = "垦丰商城-内购平台";
        $this -> view -> page_keyword = "种子网上商城";
        $this -> view -> page_description = '垦丰种子商城专注于国内外种子! ';
		$this -> goodsApi = new Purchase_Models_API_Goods();
		$this -> view -> indextag = $this -> goodsApi ->getGoodsTag('tag_id in ("23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47")');
	}


}