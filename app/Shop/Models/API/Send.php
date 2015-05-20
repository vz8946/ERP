<?php
class Shop_Models_API_Send extends Custom_Model_Dbadv
{
	public function __construct(){
	    parent::__construct();
	}
	
	public function bookQGMail($email){
		$data = array();
		$data['send_mail'] = $email;
		$data['is_send_mail'] = 1;
		if($this->isIn('shop_send_mail', array('send_mail'=>$email))) return 'error-repeat-book';
		return $this->insert('shop_send_mail', $data);		
	}
	
}