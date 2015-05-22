<?php /* Smarty version 2.6.19, created on 2014-10-22 22:07:21
         compiled from auth/login.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/styles/admin/login.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/scripts/mootools.js"></script>
<title>优信-电商ERP系统</title>
</head>
<body>
<div class="top-bg"></div>
<div class="login-box">
  <form name="loginForm" id="loginForm" method="post" onsubmit="return validate()">
  <div class="user"><span >用户名</span><input name="user_name" type="text" size="15" value="" maxlength="32" /></div>
  <div class="user"><span >密码</span><input name="password" type="password" size="16" value="" maxlength="32" /></span></div>
  <div class="user"><span >验证码</span><input name="verifyCode" onkeyup="pressVerifyCode(this)" type="text" size="10" maxlength="5" style="width:60px"/></div>
  <div class="user-code"><img class="auth-image" src="<?php echo $this -> callViewHelper('url', array(array('action'=>"auth-image",'space'=>'adminLogin',)));?>" alt="verifyCode" border="0" onclick= this.src="<?php echo $this -> callViewHelper('url', array(array('action'=>"auth-image",'space'=>'adminLogin','code'=>"",)));?>"+Math.random() style="cursor: pointer;" title="点击更换验证码" /></div>
  <div class="login-button"><a href="javascript:void(0);" onclick="document.getElementById('loginForm').submit()"><img src="/images/admin/login-button.jpg" width="107" height="24" /></a></div>
  <div class="clear-button"><a href="javascript:void(0);" onclick="document.getElementById('loginForm').reset()"><img src="/images/admin/clear-button.jpg" width="108" height="24" /></a></div>
  </form>
</div>
</body>
</html>

<script language="JavaScript">
<!--
/* set this page to top */
if (window.parent != window) {
  window.top.location.href = location.href;
}

/* display error message */
<?php if ($this->_tpl_vars['message']): ?>
alert('<?php echo $this->_tpl_vars['message']; ?>
');
<?php endif; ?>

/* focus username input tag */
document.forms['loginForm'].elements['user_name'].focus();

/**
 * 检查表单输入的内容
 */
function validate() {
    var username = $('loginForm').user_name.value.trim();
    var password = $('loginForm').password.value.trim();
    var checkcode = $('loginForm').verifyCode.value.trim();
    if (username == '') {
	    alert('用户名不能为空');
	    $('loginForm').user_name.focus();
	    return false;
    }
    if(password==''){
	    alert('密码不能为空');
	    $('loginForm').password.focus();
	    return false;
    }
    if(checkcode==''){
	    alert('验证码不能为空');
	    $('loginForm').verifyCode.focus();
	    return false;
    }
    return true;
}

/**
 * 处理验证码输入框的按键事件，将所有输入的内容转换为大写
 */
function pressVerifyCode(obj)
{
    submitByEnter();
    obj.value = obj.value.toUpperCase();
}

function submitByEnter()
{ 
    e = getEvent();
    var key = e ? (e.charCode || e.keyCode) : 0;
    if (key == 13) {
        document.getElementById('loginForm').submit();
    }
}

function getEvent()
{  
    if (document.all)   return window.event;    
    func = getEvent.caller;
    while(func != null) {
        var arg0 = func.arguments[0]; 
        if (arg0) { 
            if ((arg0.constructor == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {  
                return arg0; 
            } 
        } 
        func = func.caller; 
    }
    
    return null; 
}

//-->
</script>