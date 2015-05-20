<?php

class Custom_Controller_Action_Grid extends Zend_Controller_Action
{
    /**
     * 
     * @var Custom_Finder_Comm
     */
    protected $finder;
	protected $mdl_name;

    public function init($request, $response){
    }
    
	public function gridListAction(){

	    //列模型
	    $col_model = $this->finder->getColModel();
	    $this->view->col_model = json_encode($col_model);
	    
	    //主建
	    $this->view->pk = $this->finder->getPk();
	    
	    //快捷搜索
	    $this->view->filter = $this->finder->getFilter();
	    
	    //高级搜索
	    $this->view->advfilter = $this->finder->getAdvfilter();
	    
	    //actions 
	    $this->view->actions = $this->finder->actions();
		$this->view->mdl_name = $this->mdl_name; 
	    $this->renderScript('grid/grid-list.tpl');
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
	
}
