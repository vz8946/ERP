<?php

class Custom_Finder_Sjt extends Custom_Finder_Comm
{

    public function __construct ()
    {
        parent::__construct();
    }

    public function colModel ()
    {
        return array(
                array(
                        'field' => 'id',
                        'title' => '标识ID',
                        'pk' => true,
                        'width' => 100,
                        'dataType' => 'integer'
                ),
                array(
                        'field' => 'name',
                        'title' => '装修标识',
                        'is_title' => true,
                        'width' => 200,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'title',
                        'title' => '品牌名称',
                        'width' => 200,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'tpl',
                        'title' => '装修模板',
                        'width' => 400,
                        'sortable' => false,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'yes'
                        ),
                        'dataType' => 'string'
                )
        );
    }

    public function actions ()
    {
        return array(
                array(
                        'label' => '新的装修',
                        'target' => 'dwin',
                        'href' => '/admin/sjt/add'
                ),
                array(
                        'label' => '删除',
                        'target' => 'confirm',
                        'msg' => '确定要删除吗？',
                        'href' => '/admin/sjt/del-do'
                ),
                array(
                        'label' => '批量更新模板缓存',
                        'target' => 'ajax',
                        'href' => '/admin/sjt/refrash-batch'
                ),
        );
    }
    
    public function singleActions ($row)
    {
        return array(
                array(
                        'label' => '编辑',
                        'type'=>'winmodel',
                        'href' => '/admin/sjt/edit/id/'.$row[$this->pk]
                ),
                array(
                        'label' => '复制新建',
                        'type'=>'winmodel',
                        'href' => '/admin/sjt/edit/editcopy/Y/id/'.$row[$this->pk]
                ),
                array(
                        'label' => '删除',
                        'type'=>'del',
                        'href' => '/admin/sjt/del-do/id/'.$row[$this->pk]
                ),
                array(
                        'label' => '刷新模板',
                        'type' => 'ajax',
                        'href' => '/admin/sjt/refrash-tpl/id/'.$row[$this->pk]
                ),
        );
    }
    
}