<?php

class Custom_Finder_Refund extends Custom_Finder_Comm
{
	
	public $ord = 'add_time desc';

    public function __construct ()
    {
        parent::__construct();
    }

    public function colModel ()
    {
        return array(
                array(
                        'field' => 'refund_id',
                        'title' => '标识ID',
                        'pk' => true,
                        'width' => 50,
                        'dataType' => 'integer'
                ),
                array(
                        'field' => 'refund_sn',
                        'title' => '单号',
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
                        'field' => 'member_id',
                        'title' => '用户名',
                        'width' => 180,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'bank_type',
                        'title' => '支付类型',
                        'width' => 70
                ),
                array(
                        'field' => 'status',
                        'title' => '状态',
                        'width' => 70
                ),
                array(
                        'field' => 'money',
                        'title' => '金额',
                        'width' => 100,
                        'filter' => true,
                        'advfilter' => array(
                                'type' => 'input',
                                'searchtype' => 'has'
                        ),
                        'dataType' => 'string'
                ),
                array(
                        'field' => 'add_time',
                        'title' => '日期',
                        'width' => 400
                )
        );
    }

    public function actions ()
    {
        return array(
        );
    }
	
	public function modifyRow(&$r){
		$config_status = array('0'=>'待审核','1'=>'已退款','2'=>'已废除');
		$config_status_color = array('#ff6600','green','red');
		$config_bank_type = array('1'=>'银行打款','2'=>'支付宝');
		$r['status'] = '<span style="color:'.$config_status_color[$r['status']].';">'.$config_status[$r['status']].'</span>';
		$r['bank_type'] = $config_bank_type[$r['bank_type']];
		$r['add_time'] = date('Y-m-d H:i',$r['add_time']);
	}
    
	public function modifyList(&$list){
		$arr_member_id = array();
		$arr_member_id[] = 0;
		foreach ($list as $k => $v) {
			$arr_member_id[] = $v['member_id'];
		}
		$db_comm = new Admin_Models_DB_Comn();
		$list_member = $db_comm->getAll('shop_member',array('member_id|in'=>$arr_member_id));
		$list_member = Custom_Model_Tools::list_fkey($list_member, 'member_id');
		
		foreach ($list as $k => $v) {
			$list[$k]['member_id'] = $list_member[$v['member_id']]['email'];
		}
		
	}
	
    public function singleActions ($row)
    {
        return array(
                array(
                        'label' => '查看',
                        'type'=>'winmodel',
                        'width'=>600,
                        'height'=>350,
                        'href' => '/admin/finance/refund-view/id/'.$row[$this->pk]
                )
        );
    }
	
	
}