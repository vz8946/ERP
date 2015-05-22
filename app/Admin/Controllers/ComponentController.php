<?php
 class Admin_ComponentController extends Zend_Controller_Action {
     
    /**
     * 
     * @var Custom_Finder_Brand
     */
    private $finder;
     
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
	    parent::__construct($request, $response);
	    
	    $mdl = $this->_request->getParam('mdl');
	    
	    $class_finder = 'Custom_Finder_'.ucfirst($mdl);
	    
	    $this->finder = new $class_finder();
	    
	    $this->view->params = $this->_request->getParams();
	     
	}
	
	

	function brandsltAction(){

	    $this->view->col_model = json_encode($this->finder->getColModel());
	    $this->view->filter = $this->finder->getFilter();
        $this->view->pk = $this->finder->getPk();
	    //高级搜索
	    $this->view->advfilter = $this->finder->getAdvfilter();
	    
	}

	public function filterPanelAction(){
	    Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	    $this->view->filter = $this->finder->getFilter();
	}
	
	public function getListAction(){
	    Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	    $data = $this->finder->getGridData($this->_request->getParams());
	    echo json_encode($data);
	    exit;
	}
	
	public function getSltDataAction(){
	    Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	    $ids = $this->_request->getParam('ids');
	    $ids = trim($ids,',');
	    $list = $this->finder->getSltListByIds($ids);
	    $msg = array();
	    $msg['pk'] = $this->finder->getPk();
	    $msg['title'] = $this->finder->getTitle();
	    $msg['data'] = $list;
	    echo json_encode($msg);
	    exit;
	}
	
 }
?>
