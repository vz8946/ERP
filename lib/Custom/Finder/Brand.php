<?php

class Custom_Finder_Brand extends Custom_Finder_Comm {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function colModel(){
        return array(
            array(
                    'field'=>'brand_id',
                    'title'=>'品牌ID',
                    'pk'=>true,
                    'width'=>100,
                    'dataType'=>'integer'
            ),array(
                    'field'=>'brand_name',
                    'title'=>'品牌名称',
                    'is_title'=>true,
                    'width'=>200,
                    'filter'=>true,
                    'advfilter'=>array('type'=>'input','searchtype'=>'has'),
                    'dataType'=>'string'
            ),array(
                    'field'=>'as_name',
                    'title'=>'品牌别名',
                    'width'=>200,
                    'filter'=>true,
                    'advfilter'=>array('type'=>'input','searchtype'=>'has'),
                    'dataType'=>'string'
            ),array(
                    'field'=>'char',
                    'title'=>'品牌首字母',
                    'width'=>400,
                    'sortable'=>false,
                    'advfilter'=>array('type'=>'input','searchtype'=>'yes'),
                    'dataType'=>'string'
            )
        );
    }
    
}