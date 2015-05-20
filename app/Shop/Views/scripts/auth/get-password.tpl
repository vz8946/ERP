<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>找回密码-垦丰商城 </title>
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
    
    <div class="main mar" style="width: 800px;margin: 0px auto;">
		<h3 style="margin-bottom:15px;border-bottom: 1px dashed #777;">如果您知道您注册时使用的电子邮件地址，请输入您的电子邮件地址，我们将把您的密码发送到您的邮箱里。</h3>
		<div id="password" style="margin:0px auto;width: 600px; " >
			<div class="login_bottom">
				<form action="" method="post" name="getPassword" id="getPassword" onsubmit="return submitPwdInfo();" target="ifrmSubmit">
					<table width="100%" class="tbl-frm">
						<tr>
							<th width="80">电子邮件：</th>
							<td width="*"><input class="text" name="email" id="email" type="text" /></td>
						</tr>
						<tr>
							<th>验证码：</th>
							<td>
								 <input id="verifyCode"  name="verifyCode" onkeyup="pressVerifyCode(this)" type="text" size="5" />
							    <img src="/auth/auth-image/space/getPassword/code/{{$smarty.now}}" onclick="change_verify('verify_img','getPassword');" id="verify_img"  width="64" height="24" />
							    <a href="javascript:;" onclick="change_verify('verify_img','shopLogin');">换一个</a>
        	              </td>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<td>
							  <input type="submit" name="dosubmit" id="dosubmit" value="确定"  class="btns"/>
							</td>
						</tr>
					</table>
				</form>
				   
				   <div id="send_password_div" class="main_content" style="display:none">
					<br />
					<span id="send_password_msg" style="font-size: 14px; font-weight:bold; color:#000000; width:500px"></span>
					<br />
					<br />
					<span><a href="/index"> 返回首页 >></a></span>
				 </div>		
						
			</div>
		</div>

		<div  style="margin-top:10px;padding-top:5px;border-top: 1px dashed #777;">
			请正确填写您的注册的电子邮箱地址和手机号码，系统将把您的密码发送到您的注册邮箱，如果该邮箱地址错误，或者您已经忘记注册的邮箱地址，我们将无法为您找回
			密码，建议您 <a rel="nofollow" style="color:red;" href="/reg.html">重新注册</a> 一个帐号。
		</div>
		<div class="cleardiv"></div>
	</div>
</div>

<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
<script language="javascript" type="text/javascript" src="{{$imgBaseUrl}}/scripts/check.js"></script>
<script>
	/**
	 * 处理验证码输入框的按键事件，将所有输入的内容转换为大写
	 */
	function pressVerifyCode(obj) {
		obj.value = obj.value.toUpperCase();
	}

	function submitPwdInfo() {
		var email = $.trim($('#email').val());
		var verifyCode = $.trim($('#verifyCode').val());
		var msg = '';

		if (email == '') {
			msg += '请输入电子邮箱!\n';
		}
		if (verifyCode == '') {
			msg += '请输入验证码!\n';
		}

		if (!Check.isEmail(email)) {
			msg += '电子邮箱格式不正确!\n';
		}

		if (msg.length > 0) {
			alert(msg);
			return false;
		} else {
			$('#dosubmit').attr('disabled', true);
			return true;
		}
	}
</script>

</div>

<div class="wbox footer" style="padding-top: 10px;">  
<div class="menu">
     <a href="/help/detail-19.html">关于我们</a> |
     <a href="/help/detail-18.html">联系客服</a>|
     <a href="/help/detail-22.html">诚聘英才</a>|
     <a href="/help/detail-23.html">商务合作</a>
   </div>
  <div class="copyright">垦丰商城 All Rights Reserved 2012 <br>
   ICP证:<a href="http://www.miibeian.gov.cn/" target="_blank" rel="nofollow"> 沪ICP备10200022号-3 </a><br>
  </div>  
  <small class="footer_pic_link">
      <img src="{{$_static_}}/images/footer_pic1.jpg">
      <script src="http://kxlogo.knet.cn/seallogo.dll?sn=e13090231010042247zyhm000000&amp;size=3"></script>
      <a href="http://www.sgs.gov.cn/lz/licenseLink.do?method=licenceView&amp;entyId=20121218094238892" target="_blank" rel="nofollow"><img src="{{$_static_}}/images/280053.gif"></a>   
   </small>
</div>
</body>
</html>