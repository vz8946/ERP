<?php /* Smarty version 2.6.19, created on 2014-10-22 22:07:31
         compiled from index/index.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>优信--电子商务ERP管理平台</title>
<link href="/styles/admin/index.css" rel="stylesheet" type="text/css" />
<link href="/images/admin/alertImg/alertbox.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/scripts/mootools.js"></script>
<script language="javascript" src="/scripts/mootools-more.js"></script>
<script language="javascript" src="/scripts/admin/alertbox.js"></script>
<script language="javascript" src="/scripts/admin/common.js"></script>
<script language="javascript" src="/scripts/admin/dtree.js"></script>
<script language="javascript" type="text/javascript">
function ConfirmClose() {window.event.returnValue = '  --- 来自管理后台的提醒！';}
</script>
<script type="text/javascript" language="javascript">
function menu(){
$$(".menu_box").addEvents({
		mouseover:function(){
			$$(".menu_box ul").setStyle("display","block");
		},
		mouseout:function(){
			$$(".menu_box ul").setStyle("display","none");
		}
	})
}
</script>
</head>
<body>
<input id="Gfocus" type="text" size="1" maxlength="1" style="position: absolute; left: -1000px; top: -1000px;" />
<div class="head"> <span class="head-logo"><img src="/images/admin/logo.jpg" width="165" height="53" /></span><span>
  <ul class="head-nav" id="header_menu">
    <?php $_from = $this->_tpl_vars['menus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
        <li>
            <a href="javascript:fGo();" onclick="goMenu(<?php echo $this->_tpl_vars['menu']['menu_id']; ?>
);" id="menu-<?php echo $this->_tpl_vars['menu']['menu_id']; ?>
"><?php echo $this->_tpl_vars['menu']['menu_title']; ?>
</a>
        </li>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
  </span>
   <span class="tips">※提示：为提高办公效率，推荐使用Google Chrome浏览器</span>
   <span id="header_loading">
     <!--<img align="absmiddle" src="/images/admin/loading.gif"/>
     <span id="spnMsg">数据加载中..</span>-->
   </span>
   <span class="tips-right">您好：<?php echo $this->_tpl_vars['admin']['real_name']; ?>
:  <a href="javascript:fGo();" onclick="window.location.replace('/admin/auth/logout');"> 退出</a>  | <a href="http://www.gcyouxin.com/" target="_blank">官网</a> |  <a href="javascript:fGo();" onclick="G('/admin/index/info');">
查看系统信息</a> |  <a href="javascript:fGo();" onclick="G('/admin/index/clean-cache');">清空缓存</a></span>
  <div class="menu">
      <span><img src="/images/admin/backward.gif" width="18" height="15" /><a href="javascript:fGo();" onclick="Gurl('forward')">前进</a> </span>
      <span><img src="/images/admin/toward.gif" width="18" height="15" /><a href="javascript:fGo();" onclick="Gurl('backward')">后退</a></span>
      <span><img src="/images/admin/fresh.gif" width="18" height="15" /><a href="javascript:fGo();" onclick="Gurl('refresh')" alt="刷新" />刷新</a></span> </div>
  <span class="menu-text" id="countdown"></span>
  <div class="menu-right">
    <span><img src="/images/admin/email.gif" width="18" height="15" /><a href="javascript:fGo();" onclick="openDiv('/admin/index/send-email','ajax','发送系统邮件',480,240,true,'sel');">发送邮件</a></span>
   <!-- <span><img src="/images/admin/letter.gif" width="18" height="15" /><a href="javascript:fGo();" onclick="openDiv('/admin/index/sendmsg','ajax','发送手机短信',480,160,true,'sel');">发送短信</a></span>-->
    <span><img src="/images/admin/keyword.gif" width="18" height="15" /><a href="javascript:fGo();" onclick="openDiv('/admin/admin/change-password','ajax','修改个人密码',480,160,true,'sel');">修改密码</a></span>
   </div>
   </div>    
<div id="admin_left">
  <div class="inner">
	<div id="menu_iframe"></div>
  </div>
</div>
<div id="admin_right">
  <div class="inner">
    <iframe id="main_iframe" src="/admin/index/info/" frameborder="0" name="main_iframe"></iframe>
  </div>
</div>
<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
<script language="JavaScript">
var countdown=1440;//倒计时的时间（秒）
var myTimer=setInterval("ShowCountdown('countdown')",1000);
window.onload = function(){
    goMenu(<?php echo $this->_tpl_vars['init']; ?>
);
}
</script>
</body>
</html>