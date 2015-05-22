<?php

class Custom_Finder_Member extends Custom_Finder_Comm
{

    public function __construct ()
    {
        parent::__construct();
    }

    public function colModel ()
    {
        return array(
                array(
                        'field' => 'member_id',
                        'title' => '标识ID',
                        'pk' => true,
                        'width' => 50,
                        'dataType' => 'integer'
                ),
                array(
                        'field' => 'nick_name',
                        'title' => '昵称',
                        'is_title' => true,
                        'width' => 150,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'real_name',
                        'title' => '真实姓名',
                        'width' => 70,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'sex',
                        'title' => '性别',
                        'width' => 70
                ),
                array(
                        'field' => 'email',
                        'title' => '邮箱',
                        'width' => 150,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'mobile',
                        'title' => '手机',
                        'width' => 100,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'home_phone',
                        'title' => '家用电话',
                        'width' => 100,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'money',
                        'title' => '账户余额',
                        'width' => 100,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'frost_money',
                        'title' => '冻结额',
                        'width' => 100,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'point',
                        'title' => '积分',
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
        );
    }
    
    public function singleActions ($row)
    {
        return array(
                array(
                        'label' => '编辑',
                        'type'=>'winmodel',
                        'width'=>600,
                        'height'=>460,
                        'href' => '/admin/member/refund/id/'.$row[$this->pk]
                )
        );
    }
	
	public function where($req){
		
		$ext_where = array();
		$ext_where['status'] = '1';
		$ext_where['money|gt'] = 0.00;
		
		return parent::where($req,$ext_where);
		
	}
	
	
}