<?php
session_start();
header("Content-Type:text/html;charset=utf-8");
class testAction extends baseAction {
	private $data;
	private $info;
	private $status;
	private $url = 'http://125.211.217.178:8001/Api/ApiResult.aspx';
	private $appkey = 'Y2UwZmY0YjktYjM4ZC00ZDcxLWI2ZjgtYzViNGNlNmEzMzVi';
	private $appsecret = 'MGQyYjZmNWYtNzJjZS00MzY4LWJkYjItYmQ3MjAyZDQ0NTZk';
	private $v = '1.0';
	private $username = 'kengfengApi';
	private $pwd = '111111';
	private function sort_str($params){
		ksort($params);
		$str = '';
		if($params){
			foreach($params as $key => $val){
				$str .= $key.$val;
			}
		}
		return $str;
	}
	public function loginSign($params){
		return strtoupper(md5($this->appsecret.$this->sort_str($params).$this->appsecret));
	}
	public function Sign(){
		return md5($this->sort_str($params).$this->appsecret);
	}
	public function SessionKey($str){		
		$data = json_decode(base64_decode($str),true);
		$_SESSION['Response'] = $data;
		$params = array(
			'DomainId' => $data['DomainId'],
			'UserId' => $data['UserId'],
			'TimeStamp' => date('Y-m-d H:i:s'),
			'TimeOut' => $data['TimeOut'],
			'WebSession' => $data['WebSession'],
			'TokenKey' => $data['TokenKey']
		);
		$str = '';
		foreach($params as $key => $val){
			$str .= $key.$val;
		}
		$params['Sign'] = md5($str.$this->appsecret);
		$_SESSION['SessionKey'] = base64_encode(json_encode($params));				
		return $_SESSION['SessionKey'];
	}
	public function loginInfo($method,$data){
		$params = array(			
			'appkey'=>$this->appkey,
			'method'=>$method,
			'v'=>$this->v,
			'timestamp'=>time(),
			'sign'=>$this->sign($data),
			'paramjson'=>json_encode($data)
		);
		$str = '';
		foreach($params as $key=>$val){
			$str.="&".$key.'='.$val;
		}
		$str = ltrim($str,"&");
		$res = $this->curl_post($this->url.'?'.$str,'');
		$res = json_decode($res,true);
		return $res;
	}
	public function login(){
		$method = 'shanhuyun.system.login';
		$params = array(
			'pwd' 	   => '111111',
			'username' => 'kengfengApi'
		);
		$res = $this->loginInfo($method,$params);
		if($res['Code'] == '00'){
			echo '登录成功,SessionKey='.$this->SessionKey($res['Response']);
		}else{
			echo '登录失败!';	
		}
		return;
	}
	public function getGoodsInfos(){
		$method = 'shanhuyun.merchandise.search';		
		$data = array(
			'PageSize'=>'1'
		);
		$response = $_SESSION['Response'];		
		$params = array(
			'DomainId' => $response['DomainId'],
			'UserId' => $response['UserId'],
			'TimeStamp' => date('Y-m-d H:i:s'),
			'TimeOut' => (string)$response['TimeOut'],
			'WebSession' => $response['WebSession'],
			'TokenKey' => $response['TokenKey'],
			'AppSecret'=>$this->appsecret
		);
		$str = '';
		foreach($params as $val){
			$str.=$val;
		}
		$Sign = md5($str);
		unset($params['AppSecret']);
		$params['Sign'] = $Sign;
		$json = json_encode($params);
		$session = base64_encode($json);
		$sign = $this->loginSign($data);
		
//		$url = $this->url.'?appkey='.$this->appkey.'&method='.$method.'&v='.$this->v.'&sign='.$sign.'&paramjson='.json_encode($data).'&session='.$session;
		$param = array(
			'appkey'=>$this->appkey,
			'method'=>$method,
			'v'=>$this->v,
			'sign'=>$sign,
			'paramjson'=>json_encode($data),
			'session'=>$session
		);
		$res = $this->curl_post($this->url,$param);
		echo "<pre>";print_r($res);
		die;
	}
	/**
	 *提交json接口	
	 *url是提交地址
	 *data是提交的json参数
	**/
	private function curl_post($url,$data,$action = 'POST'){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);	  
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		$result = curl_exec($ch);
		if($result === false){
			return 'Curl error:'.curl_error($ch);
		}
		return $result;
	}
}