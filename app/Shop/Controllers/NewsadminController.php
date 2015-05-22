<?php
class NewsadminController extends Zend_Controller_Action {
		
	private $user = array(
		array('uname'=>'guoyao-newsadmin','upass'=>'guoyao20131231'),
		array('uname'=>'zhendao-newsadmin','upass'=>'zhendao20131231')
	);
	
	private $conf_article_status = array('AUDITED'=>'已审核','PENDING'=>'待审核');	
	
	private $menu = array(
		array('model'=>'cat','title'=>'类别管理','url'=>'/newsadmin/cat-index'),
		array('model'=>'article','title'=>'文章管理','url'=>'/newsadmin/index'),
		array('model'=>'tag','title'=>'标签管理','url'=>'/newsadmin/tag-index'),
		array('model'=>'article_recycle','title'=>'文章回收站','url'=>'/newsadmin/article-recycle')
	);
	
	private $loginer_name;
	
	/**
     * @var Custom_Model_Dbadv
     */	
	private $db;
	

	/**
     * @var Shop_Models_API_Newsadmin
     */	
     private $api;
	
	public function init() {
		
        //flash上传时维持session
        if (!empty($_POST['ssid']) && $_POST['ssid'] != Zend_Session::getId()) {
            Zend_Session::setId($_POST['ssid']);
        }
		session_start();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$action = $this->_request->getParam('action');

		if(!in_array($action, array('login','login-do'))){
			if(!$this->islogin()) header('Location:/newsadmin/login'); 
		}
		
		$this->db = new Custom_Model_Dbadv();
		$this->api = new Shop_Models_API_Newsadmin();
		
		//菜单
		$__L = $this->_request->getParam('__L');
		$action = $this->_request->getParam('action');
		if((!empty($__L) && $__L == 'F') || $action == 'index'){
			$c_model = $action == 'index' ? 'article' : $this->_request->getParam('__M');
			$menu = $this->menu;
			foreach ($menu as $k => $v) {
				if($c_model == $v['model']) $menu[$k]['is_c'] = true;
			}
			$this->view->menu = $menu;
		}
		
		$this->view->loginer_name = $this->loginer_name = $this->getLoginerName();
	}
	
	public function indexAction(){
		
		$finder_fields = array();
		$finder_fields[] = array('field'=>'__ck','checkbox'=>true);
		$finder_fields[] = array('title'=>'操作','field'=>'__opt','width'=>80,'align'=>'center');
		$finder_fields[] = array('title'=>'标题','field'=>'title','width'=>300);
		$finder_fields[] = array('title'=>'分类','field'=>'cat_id','width'=>200);
		$finder_fields[] = array('title'=>'作者','field'=>'author','width'=>100);
		$finder_fields[] = array('title'=>'排序','field'=>'sort','width'=>70);
		$finder_fields[] = array('title'=>'可见性','field'=>'is_view','width'=>70);
		$finder_fields[] = array('title'=>'状态','field'=>'status','width'=>70);
		$finder_fields[] = array('title'=>'添加时间','field'=>'add_time','width'=>120);
		$this->view->finder_fields = json_encode($finder_fields);
		
		$this->view->opt_view = array('Y'=>'是','N'=>'否');
		$this->view->opt_status = $this->conf_article_status;
	}
	
	public function articleRecycleAction(){
		
		$finder_fields = array();
		$finder_fields[] = array('field'=>'__ck','checkbox'=>true);		
		$finder_fields[] = array('title'=>'标题','field'=>'title','width'=>300);		
		$finder_fields[] = array('title'=>'分类','field'=>'cat_id','width'=>200);
		$finder_fields[] = array('title'=>'作者','field'=>'author','width'=>100);
		$finder_fields[] = array('title'=>'排序','field'=>'sort','width'=>70);
		$finder_fields[] = array('title'=>'可见性','field'=>'is_view','width'=>70);
		$finder_fields[] = array('title'=>'状态','field'=>'status','width'=>70);
		$finder_fields[] = array('title'=>'添加时间','field'=>'add_time','width'=>120);
		$this->view->finder_fields = json_encode($finder_fields);
		
		$this->view->opt_view = array('Y'=>'是','N'=>'否');
		$this->view->opt_status = $this->conf_article_status;
	}
	
	public function tagIndexAction(){
		$finder_fields = array();
		$finder_fields[] = array('field'=>'__ck','checkbox'=>true);
		$finder_fields[] = array('title'=>'操作','field'=>'__opt','width'=>80,'align'=>'center');
		$finder_fields[] = array('title'=>'标识名','field'=>'name','width'=>120);
		$finder_fields[] = array('title'=>'标题','field'=>'title','width'=>350);
		$finder_fields[] = array('title'=>'热门','field'=>'is_hot','width'=>60);
		$finder_fields[] = array('title'=>'所属分类','field'=>'cat_id','width'=>350);
		$this->view->finder_fields = json_encode($finder_fields);
	}
	
	public function finderListAction(){
        $list_finder = $this->api->getFinderList($this->_request->getParams());
        echo json_encode($list_finder);
        exit;		
	}
	
	public function finderRecycleListAction(){
        $list_finder = $this->api->getRecycleFinderList($this->_request->getParams());
        echo json_encode($list_finder);
        exit;		
	}
	
	public function finderListTagAction(){
        $list_finder = $this->api->getFinderListTag($this->_request->getParams());
        echo json_encode($list_finder);
        exit;		
	}
	
	public function catTreeFinderListAction(){
        $list_finder = $this->api->getTreeFinderList();
        echo json_encode($list_finder);
        exit;		
	}

	public function addAction(){
		$this->view->opt_view = array('Y'=>'是','N'=>'否');
		
		$id = $this->_request->getParam('id');
		if(!empty($id)){
			$r = $this->db->getRow('shop_seo_article',array('article_id'=>$id));
			$r['content'] = stripslashes($r['content']);
			$this->view->r = $r;
		}
		
	}
	
	public function addToTagAction(){
		$this->view->article_ids = $this->_request->getParam('article_ids');
	}
	
	public function catAddToTagAction(){
		$this->view->cat_ids = $this->_request->getParam('cat_ids');
	}
	
	public function removeFromTagAction(){
		$this->view->article_ids = $this->_request->getParam('article_ids');
	}
	
	public function catRemoveFromTagAction(){
		$this->view->cat_ids = $this->_request->getParam('cat_ids');
	}
	
	public function tagAddAction(){
		$id = $this->_request->getParam('id');
		$this->view->opt_is_hot = array('Y'=>'是','N'=>'否');
		if(!empty($id)){
			$r = $this->db->getRow('shop_seo_tag',array('tag_id'=>$id));
			$this->view->r = $r;
		}
	}
	
	public function catIndexAction(){
		$finder_fields = array();
		$finder_fields[] = array('field'=>'__ck','checkbox'=>true);
		$finder_fields[] = array('title'=>'操作','field'=>'__opt','width'=>80,'align'=>'center');
		$finder_fields[] = array('title'=>'分类名称','field'=>'cat_name','width'=>300);
		$finder_fields[] = array('title'=>'排序','field'=>'sort','width'=>100);
		$this->view->finder_fields = json_encode($finder_fields);
	}

	public function catAddAction(){
		$id = $this->_request->getParam('id');
		if(!empty($id)){
			$r = $this->db->getRow('shop_seo_cat',array('cat_id'=>$id));
			$this->view->r = $r;
			$this->view-> tmslt_cat_add_disabled = 'Y';
		}
		
	}

	public function catSubAddAction(){
		$id = $this->_request->getParam('id');
		$r = $this->db->getRow('shop_seo_cat',array('cat_id'=>$id));
		if(empty($r['cat_id'])) die;
		
		$this->view->parent_id = $r['cat_id'];
	}

	public function catDelAction(){
		
		$id = $this->_request->getParam('id');
		$r = $this->db->getRow('shop_seo_cat',array('cat_id'=>$id));
		if(empty($r['cat_id'])) die;
		
		//非叶子节点不能删除
		$count = $this->db->count('shop_seo_cat', array('parent_id'=>$id));
		if($count > 0) Custom_Model_Tools::ejd('fail','非叶子节点不能删除');

		if(!$this->db->update('shop_seo_cat',array('is_del'=>'Y'), array('cat_id'=>$id))) Custom_Model_Tools::ejd('fail','操作失败！');
		
		Custom_Model_Tools::ejd('succ','操作成功！');
		
	}

	public function tagDelAction(){
		
		$id = $this->_request->getParam('id');
		if(empty($id)) die;
		$id = trim($id,',');
		
		if(!$this->db->update('shop_seo_tag',array('is_del'=>'Y'), array('tag_id|in'=>$id))) Custom_Model_Tools::ejd('fail','操作失败！');
		
		Custom_Model_Tools::ejd('succ','操作成功！');
		
	}

	public function delAction(){
		
		$id = $this->_request->getParam('id');
		if(empty($id)) die;
		
		$id = trim($id,',');
		if(!$this->db->update('shop_seo_article', array('is_del'=>'Y'), array('article_id|in'=>$id))) Custom_Model_Tools::ejd('fail','操作失败！');
		
		Custom_Model_Tools::ejd('succ','操作成功！');
		
	}

	public function recycleBackAction(){
		
		$id = $this->_request->getParam('id');
		if(empty($id)) die;
		
		$id = trim($id,',');
		if(!$this->db->update('shop_seo_article', array('is_del'=>'N'), array('article_id|in'=>$id))) Custom_Model_Tools::ejd('fail','操作失败！');
		
		Custom_Model_Tools::ejd('succ','操作成功！');
		
	}

	public function auditAction(){
		
		if($this->loginer_name != 'guoyao-newsadmin') die;
		
		$id = $this->_request->getParam('id');
		$status = $this->_request->getParam('status');
		if(empty($id)) die;
		if(empty($status)) die;
		
		$id = trim($id,',');
		if(!$this->db->update('shop_seo_article', array('status'=>$status), array('article_id|in'=>$id))) Custom_Model_Tools::ejd('fail','操作失败！');
		
		Custom_Model_Tools::ejd('succ','操作成功！');
		
	}

	public function catAddDoAction(){
		$cat_name = $this->_request->getParam('cat_name');
		$as_name = $this->_request->getParam('as_name');
		
		if(empty($cat_name)) Custom_Model_Tools::ejd('fail','分类名称不能为空！');
		if(empty($as_name)) Custom_Model_Tools::ejd('fail','分类别名不能为空！');
		
		$cat_id = $this->_request->getParam('cat_id');
		
		
		if(empty($cat_id)){
			$count = $this->db->count('shop_seo_cat', array('as_name'=>$as_name));
			if($count>0) Custom_Model_Tools::ejd('fail','重复的别名！');
			$flag = $this->db->insert('shop_seo_cat', $this->_request->getParams());
		}else{
			$count = $this->db->count('shop_seo_cat', array('as_name'=>$as_name,'cat_id|neq'=>$cat_id));
			if($count>0) Custom_Model_Tools::ejd('fail','重复的标识！');
			$flag = $this->db->update('shop_seo_cat', $this->_request->getParams(),array('cat_id'=>$cat_id));
		}
		
		Custom_Model_Tools::ejd('succ-reload','操作成功！');	
	}

	public function tagAddDoAction(){
		
		$name = $this->_request->getParam('name');
		if(empty($name)) Custom_Model_Tools::ejd('fail','标识不能为空！');
		
		$title = $this->_request->getParam('title');
		if(empty($title)) Custom_Model_Tools::ejd('fail','名称不能为空！');
		
		$tag_id = $this->_request->getParam('tag_id');
		
		if(empty($tag_id)){
			$count = $this->db->count('shop_seo_tag', array('name'=>$name));
			if($count>0) Custom_Model_Tools::ejd('fail','重复的标识！');
			$this->db->insert('shop_seo_tag', $this->_request->getParams());
		}else{
			$count = $this->db->count('shop_seo_tag', array('name'=>$name,'tag_id|neq'=>$tag_id));
			if($count>0) Custom_Model_Tools::ejd('fail','重复的标识！');
			$this->db->update('shop_seo_tag', $this->_request->getParams(),array('tag_id'=>$tag_id));
		}
		
		Custom_Model_Tools::ejd('succ-reload','操作成功！');	
	}

	public function addDoAction(){

		$title = $this->_request->getParam('title');
		if(empty($title)) Custom_Model_Tools::ejd('fail','分类名称不能为空！');
		
		$cat_id = $this->_request->getParam('cat_id');
		if(empty($cat_id)) Custom_Model_Tools::ejd('fail','分类不能为空！');

		$article_id = $this->_request->getParam('article_id');
		
		if(empty($article_id)){
			$this->_request->setParam('add_time', mktime());
			$flag = $this->db->insert('shop_seo_article', $this->_request->getParams());
		}else{
			$flag = $this->db->update('shop_seo_article', $this->_request->getParams(),array('article_id'=>$article_id));
		}
		if($flag){
			Custom_Model_Tools::ejd('succ-reload','操作成功！');	
		}
		
		Custom_Model_Tools::ejd('fail','操作失败！');
	}

	public function addToTagDoAction(){
		
		$article_ids = $this->_request->getParam('article_ids');
		if(empty($article_ids)) die;
		$article_ids = trim($article_ids,',');
		$name = $this->_request->getParam('name');
		if(empty($name)) Custom_Model_Tools::ejd('fail','标识不能为空！');

		//标签存在
		$r_tag = $this->db->getRow('shop_seo_tag',array('name'=>$name));
		if(empty($r_tag['tag_id'])) Custom_Model_Tools::ejd('fail','标识不存在！');
		
		$config_tag = unserialize($r_tag['config']);
		
		
		$arr_article_id = (array)$config_tag['arr_article_id'];
		
		$arr_article_id = array_unique(array_merge($arr_article_id,explode(',',$article_ids)));
		$arr_article_id = array_filter($arr_article_id);

		$config_tag['arr_article_id'] = $arr_article_id; 
		
		$this->db->update('shop_seo_tag', array('config'=>serialize($config_tag)),array('name'=>$name));
		
		Custom_Model_Tools::ejd('succ-reload','操作成功！');	
	}

	public function catAddToTagDoAction(){
		
		$cat_ids = $this->_request->getParam('cat_ids');
		if(empty($cat_ids)) die;
		$cat_ids = trim($cat_ids,',');
		$name = $this->_request->getParam('name');
		if(empty($name)) Custom_Model_Tools::ejd('fail','标识不能为空！');

		//标签存在
		$r_tag = $this->db->getRow('shop_seo_tag',array('name'=>$name));
		if(empty($r_tag['tag_id'])) Custom_Model_Tools::ejd('fail','标识不存在！');
		
		$config_tag = unserialize($r_tag['config']);
		
		$arr_cat_id = !empty($config_tag['arr_cat_id']) ? (array)$config_tag['arr_cat_id'] : array(); 
		
		$arr_cat_id = array_unique(array_merge($arr_cat_id,explode(',',$cat_ids)));
		$arr_cat_id = array_filter($arr_cat_id);

		$config_tag['arr_cat_id'] = $arr_cat_id; 
		
		$this->db->update('shop_seo_tag', array('config'=>serialize($config_tag)),array('name'=>$name));
		
		Custom_Model_Tools::ejd('succ-reload','操作成功！');	
	}

	public function removeFromTagDoAction(){
		
		$article_ids = $this->_request->getParam('article_ids');
		if(empty($article_ids)) die;
		$article_ids = trim($article_ids,',');
		$name = $this->_request->getParam('name');
		if(empty($name)) Custom_Model_Tools::ejd('fail','标识不能为空！');

		//标签存在
		$r_tag = $this->db->getRow('shop_seo_tag',array('name'=>$name));
		if(empty($r_tag['tag_id'])) Custom_Model_Tools::ejd('fail','标识不存在！');
		
		$config_tag = unserialize($r_tag['config']);
		
		$arr_article_id = (array)$config_tag['arr_article_id'];
		
		$arr_article_id = array_unique(array_diff($arr_article_id,explode(',',$article_ids)));
		$arr_article_id = array_filter($arr_article_id);

		$config_tag['arr_article_id'] = $arr_article_id; 
		
		$this->db->update('shop_seo_tag', array('config'=>serialize($config_tag)),array('name'=>$name));
		
		Custom_Model_Tools::ejd('succ-reload','操作成功！');	
	}

	public function catRemoveFromTagDoAction(){
		$cat_ids = $this->_request->getParam('cat_ids');
		if(empty($cat_ids)) die;
		$cat_ids = trim($cat_ids,',');
		$name = $this->_request->getParam('name');
		if(empty($name)) Custom_Model_Tools::ejd('fail','标识不能为空！');

		//标签存在
		$r_tag = $this->db->getRow('shop_seo_tag',array('name'=>$name));
		if(empty($r_tag['tag_id'])) Custom_Model_Tools::ejd('fail','标识不存在！');
		
		$config_tag = unserialize($r_tag['config']);
		$arr_cat_id = (array)$config_tag['arr_cat_id'];
		$arr_cat_id = array_unique(array_diff($arr_cat_id,explode(',',$cat_ids)));
		$arr_cat_id = array_filter($arr_cat_id);

		$config_tag['arr_cat_id'] = $arr_cat_id; 
		
		$this->db->update('shop_seo_tag', array('config'=>serialize($config_tag)),array('name'=>$name));
		
		Custom_Model_Tools::ejd('succ-reload','操作成功！');	
	}

	public function loginAction(){
		if($this->islogin()){
			header('Location:/newsadmin');
		}
	}
	
	public function loginDoAction(){
		
		$uname = $this->_request->getParam('uname');
		$upass = $this->_request->getParam('upass');
		$vcode = $this->_request->getParam('vcode');

		if(empty($uname)) Custom_Model_Tools::ejd('fail','用户名不能为空！');
		if(empty($upass)) Custom_Model_Tools::ejd('fail','密码不能为空！');
		if(empty($vcode)) Custom_Model_Tools::ejd('fail','验证码不能为空！');
		
        $authImage = new Custom_Model_AuthImage('shopLogin');
        if (!$authImage -> checkCode($vcode)){
        	Custom_Model_Tools::ejd('fail','验证码不正确！');
        }
		
		foreach ($this->user as $k => $v) {
			if($uname == $v['uname'] && $upass == $v['upass']){
				$newsadmin = array();
				$newsadmin['name'] = $v['uname'];			
				$newsadmin['login_code'] = md5($v['uname'].$v['upass'].'guoyao'); 
				$_SESSION['newsadmin'] = $newsadmin;
				
				Custom_Model_Tools::ejd('succ-href','登陆成功！','/newsadmin');
			}			
		}
		
		Custom_Model_Tools::ejd('fail','错误的用户名或密码！');
		
	}
	
	public function loginoutAction(){
		unset($_SESSION['newsadmin']);
		header('Location:/newsadmin/login');
		exit;
	}
	
	private function getLoginerName(){
		return $_SESSION['newsadmin']['name'];
	}
	
	private function islogin(){
		if(!empty($_SESSION['newsadmin'])){
			$upass = '';
			foreach ($this->user as $k => $v) {
				if($v['uname'] == $_SESSION['newsadmin']['name']){
					$upass = $v['upass'];
					break;
				}
			}
			
			if($_SESSION['newsadmin']['login_code'] == md5($_SESSION['newsadmin']['name'].$upass.'guoyao')){
				return true;
			}
		}
		return false;
	}
	
	public function xhuploadAction(){
		/*!
		 * upload demo for php
		 * @requires xhEditor
		 * 
		 * @author Yanis.Wang<yanis.wang@gmail.com>
		 * @site http://xheditor.com/
		 * @licence LGPL(http://www.opensource.org/licenses/lgpl-license.php)
		 * 
		 * @Version: 0.9.6 (build 111027)
		 * 
		 * 注1：本程序仅为演示用，请您务必根据自己需求进行相应修改，或者重开发
		 * 注2：本程序特别针对HTML5上传，加入了特殊处理
		 */
		header('Content-Type: text/html; charset=UTF-8');
		
		$inputName='filedata';//表单文件域name
		$attachDir=SYSROOT.'/www/upload/xheditor';//上传文件保存路径，结尾不要带/
		$dirType=1;//1:按天存入目录 2:按月存入目录 3:按扩展名存目录  建议使用按天存
		$maxAttachSize=2097152;//最大上传大小，默认是2M
		$upExt='jpg,jpeg,gif,png';//上传扩展名
		$msgType=2;//返回上传参数的格式：1，只返回url，2，返回参数数组
		$immediate=isset($_GET['immediate'])?$_GET['immediate']:0;//立即上传模式，仅为演示用
		ini_set('date.timezone','Asia/Shanghai');//时区
		
		$err = "";
		$msg = "''";
		$tempPath=$attachDir.'/'.date("YmdHis").mt_rand(10000,99999).'.tmp';
		
	    $xml = new Custom_Config_Xml();
	    $config=$xml->getConfig();

	    $domain = $config -> masterdomain;
		
		
		$localName=$domain;
		$img_url = $domain.'/upload/xheditor/';
		
		if(isset($_SERVER['HTTP_CONTENT_DISPOSITION'])&&preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i',$_SERVER['HTTP_CONTENT_DISPOSITION'],$info)){//HTML5上传
			file_put_contents($tempPath,file_get_contents("php://input"));
			$localName=urldecode($info[2]);
		}
		else{//标准表单式上传
			$upfile=@$_FILES[$inputName];
			if(!isset($upfile))$err='文件域的name错误';
			elseif(!empty($upfile['error'])){
				switch($upfile['error'])
				{
					case '1':
						$err = '文件大小超过了php.ini定义的upload_max_filesize值';
						break;
					case '2':
						$err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
						break;
					case '3':
						$err = '文件上传不完全';
						break;
					case '4':
						$err = '无文件上传';
						break;
					case '6':
						$err = '缺少临时文件夹';
						break;
					case '7':
						$err = '写文件失败';
						break;
					case '8':
						$err = '上传被其它扩展中断';
						break;
					case '999':
					default:
						$err = '无有效错误代码';
				}
			}
			elseif(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none')$err = '无文件上传';
			else{
				move_uploaded_file($upfile['tmp_name'],$tempPath);
				$localName=$upfile['name'];
			}
		}
		
		if($err==''){
			$fileInfo=pathinfo($localName);
			$extension=$fileInfo['extension'];
			if(preg_match('/^('.str_replace(',','|',$upExt).')$/i',$extension))
			{
				$bytes=filesize($tempPath);
				if($bytes > $maxAttachSize)$err='请不要上传大小超过'.$this->formatBytes($maxAttachSize).'的文件';
				else
				{
					switch($dirType)
					{
						case 1: $attachSubDir = 'day_'.date('ymd'); break;
						case 2: $attachSubDir = 'month_'.date('ym'); break;
						case 3: $attachSubDir = 'ext_'.$extension; break;
					}
					$attachDir = $attachDir.'/'.$attachSubDir;
					if(!is_dir($attachDir))
					{
						@mkdir($attachDir, 0777);
						@fclose(fopen($attachDir.'/index.htm', 'w'));
					}
					PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
					$newFilename=date("YmdHis").mt_rand(1000,9999).'.'.$extension;
					$targetPath = $attachDir.'/'.$newFilename;
					rename($tempPath,$targetPath);
					@chmod($targetPath,0755);
					$targetPath=$this->jsonString($targetPath);
					$img_url = $img_url.$attachSubDir.'/'.$newFilename;
					if($immediate=='1')$targetPath='!'.$targetPath;
					if($msgType==1)$msg="'$targetPath'";
					else $msg="{'url':'".$img_url."','localname':'".$this->jsonString($localName)."','id':'1'}";//id参数固定不变，仅供演示，实际项目中可以是数据库ID
				}
			}
			else $err='上传文件扩展名必需为：'.$upExt;
		
			@unlink($tempPath);
		}
		
		echo "{'err':'".$this->jsonString($err)."','msg':".$msg."}";
		
		exit;
	}

	private function jsonString($str){
		return preg_replace("/([\\\\\/'])/",'\\\$1',$str);
	}
	
	private function formatBytes($bytes) {
		if($bytes >= 1073741824) {
			$bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
		} elseif($bytes >= 1048576) {
			$bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
		} elseif($bytes >= 1024) {
			$bytes = round($bytes / 1024 * 100) / 100 . 'KB';
		} else {
			$bytes = $bytes . 'Bytes';
		}
		return $bytes;
	}
	
	public function uploadifyAction(){

        if(empty($_FILES['Filedata']['name'])) die;
        
        $path = '';
        if( is_file($_FILES['Filedata']['tmp_name']) ) {
            $upload_path = 'upload/uploadify';
            $upload = new Custom_Model_Upload('Filedata', $upload_path );
            $list_upfile = $upload -> up( false );
            $status = true;
            $path = $list_upfile[0]['saveto'];
            $msg = '';
            if($upload->error()){
                $status = false;
                $path = '';
                $msg = $upload->error();
            }
            echo json_encode(array(
                'status'=>$status,
                'msg'=>$msg,
                'path'=>$path
            ));
            
            exit;
        }
        
        exit;		
	}

}