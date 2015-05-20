<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>用户登录-垦丰商城 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="image/x-icon" href="{{$_static_}}/images/home.ico" rel="Shortcut Icon">
<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,login.css&v={{$sys_version}}.css" rel="stylesheet" />
<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,common.js,login.js&v={{$sys_version}}.js" type="text/jscript"></script>	
</head>
<body>
<div class="main">
	<div class="header"><img src="{{$_static_}}/images/login/login_top.jpg" usemap="#Map" width="990" height="94" />
	 <map id="Map" name="Map">
      <area href="/" coords="3,3,228,98" shape="rect">
   </map>
	</div>
    <div class="content">
      <div class="ad"> {{widget class="AdvertWidget"  id="17"}}</div>
      <form action="/auth/login/" method="post"  onsubmit="return userLogin();" name="loginForm" id="loginForm">
{{if $goto}}
<input type="hidden" name="goto" value="{{$goto}}" />
{{/if}}
<input type="hidden" name="refer" value="{{$refer}}" />
      <div class="form">           
        	<p class="link_reg">还不是垦丰会员？ <a href="/reg.html">立即注册</a></p>
            <p><img src="{{$_static_}}/images/login/welcom.jpg" width="104" height="41" /></p>            
            <p id="msg_box" style="color:red;display:none;padding:2px 0px 2px 70px"></p> 
            <p class="label"><label>账  号</label><input   id="user_name" name="user_name"  type="text"   {{if $isRemUserName && $cookieUserName}}value="{{$cookieUserName}}"{{/if}}  /></p>
       		<p class="label pwd"><label>密  码</label><input id="pwd"  name="password" type="password" /><span>
       		<a href="/auth/get-password">忘记密码</a></span></p>
            <p class="label code"><label>验证码</label><input name="verifyCode"  maxlength="5" id="vcode" type="text"  />
            <img src="/auth/auth-image/space/shopLogin/code/{{$smarty.now}}" onclick="change_verify('verify_img','shopLogin');" id="verify_img"  width="64" height="24" /><a href="javascript:;" onclick="change_verify('verify_img','shopLogin');">换一个</a></p>
        	<p class="remember"><input name="auto_login" type="checkbox" value="1" />记住登陆</p>
            <p class="login">
              <input type="image" src="{{$_static_}}/images/login/btn_login.jpg" alt="登录" />
             <div class="cooperation">
             <span>您也可以用合作伙伴账号登陆：</span>
            <em>

	    <a href="/auth/alipaylogin"><img src="{{$_static_}}/images/login/alipay.jpg" width="21" height="20" /></a>
            <a href="/auth/qqlogin"><img src="{{$_static_}}/images/login/i_qq.jpg" width="21" height="20" /></a>
            <a href="https://oauth.taobao.com/authorize?response_type=code&client_id=21573157&redirect_uri=http%3A%2F%2Fwww.1jiankang.com%2Fauth%2Ftaobaocallback&state=1"><img src="{{$_static_}}/images/login/i_taobao.jpg" width="19" height="20" /></a>
            <a href="https://api.weibo.com/oauth2/authorize?client_id=1862192494&redirect_uri=http%3A%2F%2Fwww.1jiankang.com%2Fauth%2Fweibocallback&response_type=code"><img src="{{$_static_}}/images/login/i_weibo.jpg" width="22" height="20" /></a>
            <a href="/auth/kaixinlogin"><img src="{{$_static_}}/images/login/i_kaixin.jpg" width="21" height="20" /></a>
            <a href="http://reg.163.com/open/oauth2/authorize.do?client_id=7449660002&redirect_uri=http%3A%2F%2Fwww.1jiankang.com%2Fauth%2Fwangyicallback&response_type=code"><img src="{{$_static_}}/images/login/i_wangyi.jpg" width="15" height="18" /></a> 
           </em>
            </div>
      </div>   
   </form>
    </div>
</div>

<div class="wbox footer" style="padding-top: 10px;">  
<div class="menu">
     <a href="/help/detail-19.html">关于我们</a> |
     <a href="/help/detail-18.html">联系客服</a>|
     <a href="/help/detail-22.html">诚聘英才</a>|
     <a href="/help/detail-23.html">商务合作</a>
   </div>
  <div class="copyright">垦丰商城 All Rights Reserved 2012 本站网络实名： 垦丰商城 <br>
   ICP证:<a href="http://www.miibeian.gov.cn/" target="_blank" rel="nofollow"> 沪ICP备10200022号-3 </a><br>
  </div>
 <small class="footer_pic_link">
      <img src="{{$_static_}}/images/footer_pic1.jpg">
      <script src="http://kxlogo.knet.cn/seallogo.dll?sn=e13090231010042247zyhm000000&amp;size=3"></script>
      <a href="http://www.sgs.gov.cn/lz/licenseLink.do?method=licenceView&amp;entyId=20121218094238892" target="_blank" rel="nofollow"><img src="{{$_static_}}/images/280053.gif"></a>   
   </small>
</div>

<script type="text/javascript">
$(function(){
	$('#user_name').bind('focus',function(){$(this).attr('class','txt_focus')});
	$('#user_name').bind('blur',function(){$(this).removeAttr('class')});
	    
    $('#pwd').bind('focus',function(){$(this).attr('class','txt_focus')});
    $('#pwd').bind('blur',function(){$(this).removeAttr('class')});
    
    $('#vcode').bind('focus',function(){$(this).attr('class','txt_focus')});
    $('#vcode').bind('blur',function(){$(this).removeAttr('class')});
    $('#vcode').bind('keyup',function(){parseUpperCase(this)});   
});
</script>
</body>
</html>