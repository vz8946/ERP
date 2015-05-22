<?php

class Custom_Finder_Goods extends Custom_Finder_Comm {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function colModel(){
        return array(
            array(
                    'field'=>'goods_id',
                    'title'=>'商品ID',
                    'pk'=>true,
                    'width'=>100,
                    'dataType'=>'integer'
            ),array(
                    'field'=>'goods_sn',
                    'title'=>'商品编号',
                    'width'=>100,
                    'filter'=>true,
                    'dataType'=>'string'
            ),array(
                    'field'=>'goods_name',
                    'title'=>'商品名称',
                    'is_title'=>true,
                    'width'=>300,
                    'filter'=>true,
                    'advfilter'=>array('type'=>'input','searchtype'=>'has'),
                    'dataType'=>'string'
            )
        );
    }
    
}