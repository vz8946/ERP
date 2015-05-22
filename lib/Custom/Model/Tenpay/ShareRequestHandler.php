<?php

/**
 * �����¼������
 * ============================================================================
 * api˵����
 * init(),��ʼ��������Ĭ�ϸ�һЩ������ֵ����cmdno,date�ȡ�
 * getGateURL()/setGateURL(),��ȡ/������ڵ�ַ,����������ֵ
 * getKey()/setKey(),��ȡ/������Կ
 * getParameter()/setParameter(),��ȡ/���ò���ֵ
 * getAllParameters(),��ȡ���в���
 * getRequestURL(),��ȡ������������URL
 * doSend(),�ض��򵽲Ƹ�֧ͨ��
 * getDebugInfo(),��ȡdebug��Ϣ
 * 
 * ============================================================================
 *
 */

include_once 'RequestHandler.php';

class Custom_Model_Tenpay_ShareRequestHandler extends Custom_Model_Tenpay_RequestHandler {
	
	function __construct() {
		$this->MediPayRequestHandler();
	}
	
	function MediPayRequestHandler() {
		//Ĭ��֧�����ص�ַ
		$this->setGateURL("https://www.tenpay.com/cgi-bin/v1.0/service_gate.cgi");	
	}
	
	/**
	*@Override
	*��ʼ��������Ĭ�ϸ�һЩ������ֵ��
	*/
	function init() {
		//ǩ������
		$this->setParameter("sign_type", "md5");
		
		//��Կ����
		$this->setParameter("sign_encrypt_keyid",  "0");
		
		//�������ͣ�GBK, UTF-8
		$this->setParameter("input_charset", "GBK");
		
		//��������
		$this->setParameter("service", "login");
		
		//�Ƹ�ͨ�����˻����������̻��Ż�Ƹ�ͨ�˺�
		$this->setParameter("chnid", "");
		
		//�����˺����ͣ�0Ϊ�̻��ţ�1Ϊ�Ƹ�ͨ�˺�
		$this->setParameter("chtype", "0");
		
		//��¼�ɹ���ص�url
		$this->setParameter("redirect_url",  "");
		
		//���Ӳ������ص�ʱԭ�����أ����Ա���ҵ���url
		$this->setParameter("attach",  "");
		
		//��������ʱ��ʱ���
		$this->setParameter("tmstamp",  (string)time());
		
	}
}

?>