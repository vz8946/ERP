<?php
class Admin_BrandController extends Zend_Controller_Action {
    /**
     *
     * @var Admin_Models_API_Brand
     */
    private $_api = null;
    const ADD_SUCCESS = '添加品牌成功!';
    const EDIT_SUCCESS = '编辑品牌成功!';
    
    /**
     * 初始化对象
     *
     * @return void
     */
    public function init() {
        $this->_api = new Admin_Models_API_Brand ();
    }
    
    /**
     * 默认动作
     *
     * @return void
     */
    public function indexAction() {
        $page = ( int ) $this->_request->getParam ( 'page', 1 );
        $search = $this->_request->getParams ();
        $datas = $this->_api->get ( null, '1' );
        if ($datas) {
            $total = count ( $datas );
        } else
            $total = 0;
        $datas = $this->_api->get ( $search, 'brand_id,band_sort,brand_name,big_logo,small_logo,bluk,as_name,ispinpaicheng,status', null, $page, 20 );
        foreach ( $datas as $num => $data ) {
            $datas [$num] ['add_time'] = ($datas [$num] ['add_time'] > 0) ? date ( 'Y-m-d H:i:s', $datas [$num] ['add_time'] ) : '';
            $datas [$num] ['status'] = $this->_api->ajaxStatus ( $this->getFrontController ()->getBaseUrl () . $this->_helper->url ( 'status' ), $datas [$num] ['brand_id'], $datas [$num] ['status'] );
            $datas [$num] ['brand_goods_num'] = $this->_api->getGoodsBrandNum ( $datas [$num] ['brand_id'] );
        }
        $this->view->datas = $datas;
        $this->view->param = $this->_request->getParams ();
        $pageNav = new Custom_Model_PageNav ( $total, 20, 'ajax_search' );
        $this->view->pageNav = $pageNav->getNavigation ();
        $this->view->opt_yn = array (
                'Y' => '是',
                'N' => '否' 
        );
    }
    
    /**
     * 选择品牌
     *
     * @return void
     */
    public function selAction() {
        $job = $this->_request->getParam ( 'job', null );
        $page = ( int ) $this->_request->getParam ( 'page', 1 );
        $search = $this->_request->getParams ();
        if ($job) {
            $search ['filter'] = "";
            $data = $this->_api->get ( $search, 'brand_id,band_sort,brand_name,big_logo,small_logo,bluk,as_name,ispinpaicheng,status', null, $page );
            $datas = $this->_api->get ( null, '1' );
            if ($datas) {
                $total = count ( $datas );
            } else
                $total = 0;
            
            $this->view->datas = $data;
        }
        $this->view->param = $this->_request->getParams ();
        $pageNav = new Custom_Model_PageNav ( $total, null, 'ajax_search_goods' );
        $this->view->pageNav = $pageNav->getNavigation ();
    }
    
    /**
     * 添加动作
     *
     * @return void
     */
    public function addAction() {
        if ($this->_request->isPost ()) {
            $postdata = $this->_request->getPost ();
            $result = $this->_api->edit ( $postdata );
            if ($result) {
                Custom_Model_Message::showMessage ( self::ADD_SUCCESS, $this->getFrontController ()->getBaseUrl () . '/admin/brand/index' );
            } else {
                Custom_Model_Message::showMessage ( $this->_api->error () );
            }
        } else {
            $this->view->action = 'add';
            $this->view->checktree = $this->_api->getCheckTree ();
            $this->render ( 'edit' );
        }
    }
    
    /**
     * 编辑动作
     *
     * @return void
     */
    public function editAction() {
        $id = ( int ) $this->_request->getParam ( 'id', null );
        if ($id > 0) {
            if ($this->_request->isPost ()) {
                $postdata = $this->_request->getPost ();
                $result = $this->_api->edit ( $postdata, $id );
                if ($result) {
                    
                    // 更新商品表的as_name
                    $this->_api->updateGoodsAsname ( $id );
                    
                    Custom_Model_Message::showMessage ( self::EDIT_SUCCESS, $this->getFrontController ()->getBaseUrl () . '/admin/brand/index' );
                } else {
                    Custom_Model_Message::showMessage ( $this->_api->error () );
                }
            } else {
                $this->view->action = 'edit';
                $this->view->checktree = $this->_api->getCheckTree ();
                $data = array_shift ( $this->_api->get ( "brand_id=$id" ) );
                $this->view->data = $data;
            }
        } else {
            Custom_Model_Message::showMessage ( 'error!', 'event', 1250, 'Gurl()' );
        }
    }
    
    /**
     * 删除动作
     *
     * @return void
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        if ($id > 0) {
            $result = $this->_api->delete ( $id );
            if (! $result) {
                exit ( $this->_api->error () );
            }
        } else {
            exit ( 'error!' );
        }
    }
    
    /**
     * 更改状态动作
     *
     * @return void
     */
    public function statusAction() {
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        $status = ( int ) $this->_request->getParam ( 'status', 0 );
        
        if ($id > 0) {
            $this->_api->changeStatus ( $id, $status );
        } else {
            Custom_Model_Message::showMessage ( 'error!' );
        }
        echo $this->_api->ajaxStatus ( $this->getFrontController ()->getBaseUrl () . $this->_helper->url ( 'status' ), $id, $status );
    }
    
    /**
     * 切换品牌馆状态
     *
     * @return void
     */
    public function toggleBlukAction() {
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        $status = ( int ) $this->_request->getParam ( 'status', 0 );
        
        if ($id <= 0) die('failure'); 
        
        $cs = $this->_api->toggleBluk($id);
        echo $cs == 0 ? '否' : '是';
        exit;
    }
    
    /**
     * 切换品牌城状态
     *
     * @return void
     */
    public function toggleIspinpaichengAction() {
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        $status = ( int ) $this->_request->getParam ( 'status', 0 );
        
        if ($id <= 0) die('failure'); 
        
        $cs = $this->_api->toggleIspinpaicheng($id);
        echo $cs == 0 ? '否' : '是';
        exit;
    }
    
    /**
     * ajax更新数据
     *
     * @return void
     */
    public function ajaxupdateAction() {
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        $field = $this->_request->getParam ( 'field', null );
        $val = $this->_request->getParam ( 'val', null );
        if ($id > 0) {
            $this->_api->ajaxUpdate ( $id, $field, $val );
        } else {
            exit ( 'error!' );
        }
        if ($field == 'brand_name') {
            $data = array_shift ( $this->_api->get ( "brand_id = {$id}", 'attr_id' ) );
        }
    }
    /**
     * 品牌产品标签
     */
    public function tagAction() {
        $id = ( int ) $this->_request->getParam ( 'id', null );
        $this->view->data = $this->_api->getGoodsByBrandTag ( $id );
        ;
    }
    /**
     * 修改品牌推荐商品
     */
    public function brandTagAction() {
        $id = ( int ) $this->_request->getParam ( 'id', null );
        $result = $this->_api->updateBrandTag ( $this->_request->getPost (), $id );
        if ($result) {
            Custom_Model_Message::showMessage ( self::EDIT_SUCCESS, $this->getFrontController ()->getBaseUrl () . '/admin/brand/index' );
        } else {
            Custom_Model_Message::showMessage ( $this->_api->error () );
        }
    }
    public function refreshCharCacheAction() {
        $apiBrand = new Admin_Models_API_Brand ();
        $list_brand = $apiBrand->getAll ();
        $list_char_brand = array ();
        $conf_char_pos = array (
                'A' => '-80',
                'B' => '-80',
                'C' => '-80',
                'D' => '-80',
                'E' => '-80',
                'F' => '-80',
                'G' => '-80',
                'H' => '-100',
                'I' => '-180',
                'J' => '-180',
                'K' => '-180',
                'L' => '-210',
                'M' => '-240',
                'N' => '-280',
                'O' => '-320',
                'P' => '-360',
                'Q' => '-400',
                'R' => '-430',
                'S' => '-460',
                'T' => '-500',
                'U' => '-540',
                'V' => '-570',
                'W' => '-600',
                'X' => '-630',
                'Y' => '-670',
                'Z' => '-650' 
        );
        
        foreach ( $list_brand as $k => $v ) {
            if (empty ( $v ['char'] ))
                continue;
            if (! array_key_exists ( $v ['char'], $conf_char_pos ))
                continue;
            
            $list_char_brand [$v ['char']] [] = $v;
        }
        $content = '<div class="letter" id="letter_menu"><ul>';
        ksort ( $list_char_brand );
        foreach ( $list_char_brand as $k => $v ) {
            $content .= '<li class="letter_li" value="' . $k . '">';
            $content .= '<a href="javascript:void(0);" value="' . $k . '" id="' . $k . '" rel="nofollow">' . $k . '</a><i id="small_' . $k . '" style="display: none;"></i>';
            $content .= '<div id="menuList_' . $k . '" class="submenu" style="left:' . $conf_char_pos [$k] . 'px;display: none; ">';
            $content .= '<div class="list">';
            
            foreach ( $v as $kk => $vv ) {
                $content .= '<a href="/b-' . $vv ['as_name'] . '" target="_blank">' . $vv ['brand_name'] . '</a>';
            }
            
            $content .= '</div>';
            $content .= '</div>';
            $content .= '</li>';
            $content .= '';
        }
        $content .= '</ul></div>';
        
        $objFile = new Custom_Model_File ();
        $objFile->writefile ( SHOP_TPL_ROOT . '_library/banner-letter.tpl', $content );
        
        Custom_Model_Message::showAlert("更新缓存成功！",true,-1);
    }
}
