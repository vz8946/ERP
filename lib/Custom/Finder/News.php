<?php

class Custom_Finder_News extends Custom_Finder_Comm {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function colModel(){
        return array(
            array(
                    'field'=>'id',
                    'title'=>'标识ID',
                    'pk'=>true,
                    'width'=>50,
                    'dataType'=>'integer'
            ),array(
                    'field'=>'title',
                    'title'=>'资讯标题',
                    'is_title'=>true,
                    'width'=>300,
                    'filter'=>true,
                    'advfilter'=>array('type'=>'input','searchtype'=>'has'),
                    'dataType'=>'string'
            ),array(
                    'field'=>'asName',
                    'title'=>'别名标识',
                    'width'=>160,
                    'filter'=>true,
                    'advfilter'=>array('type'=>'input','searchtype'=>'has'),
                    'dataType'=>'string'
            ),array(
                    'field'=>'ncName',
                    'title'=>'别名',
                    'width'=>100,
                    'filter'=>true,
                    'advfilter'=>array('type'=>'input','searchtype'=>'has'),
                    'dataType'=>'string'
            )
        );
    }
    
}