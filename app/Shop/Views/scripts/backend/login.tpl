<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,cart.css{{$css_more}}&v={{$sys_version}}.css" rel="stylesheet" />
<script>var site_url='{{$_static_}}'; var jumpurl= '{{$url}}';</script>
<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,common.js{{$js_more}}&v={{$sys_version}}.js" ></script>

<title>垦丰&mdash;商户平台</title>
</head>

<body style="background:#f7f7f7;">
<div class="header">
	<div class="h_main"><img src="{{$imgBaseUrl}}/images/backend/logo_m_service.jpg" width="426" height="79" /></div>
</div>
<form method="post" name="loginForm" id="loginForm" action="{{url}}">
<div class="login">
  <div class="l_main">
  	<input name="username" id="username"type="text" class="name" />
    <input name="password" id="password" type="password" class="pwd" />
    <input name="verify_code" id="verify_code" type="text" class="check" maxlength="5" onkeyup="pressVerifyCode(this)"/>
    <div class="code"><img id="auth_image" src="/auth/auth-image/space/backendLogin/code/{{$random}}" onclick="this.src='/auth/auth-image/space/backendLogin/code/'+Math.random()" style="cursor: pointer;" width="80" height="25" /></div>
    <a href="javascript:void(0);" class="change" onclick="document.getElementById('auth_image').src='/auth/auth-image/space/backendLogin/code/'+Math.random()"></a>
    <a href="javascript:void(0);" class="btn_login" onclick="check()"></a>
  </div>
</div>
</form>
</body>
</html>

<script>
function check()
{
    if (document.getElementById('username').value == '') {
        alert('商户名不能为空！');
        return;
    }
    if (document.getElementById('password').value == '') {
        alert('密码不能为空！');
        return;
    }
    if (document.getElementById('verify_code').value == '') {
        alert('验证码不能为空！');
        return;
    }
    document.getElementById('loginForm').submit();
}

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
        check();
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

document.getElementById('auth_image').src = '/auth/auth-image/space/backendLogin/code/' + Math.random();

{{if $error}}
{{if $error eq 'empty username'}}alert('用户名不能为空!');
{{elseif $error eq 'empty password'}}alert('密码不能为空!');
{{elseif $error eq 'empty verify code'}}alert('验证码不能为空!');
{{elseif $error eq 'username or password invalid'}}alert('用户名或密码错误!');
{{elseif $error eq 'verify code invalid'}}alert('验证码错误!');
{{/if}}
{{/if}}

</script>