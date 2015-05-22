<?php /* Smarty version 2.6.19, created on 2014-10-30 10:42:37
         compiled from flow/fast-login.tpl */ ?>
<!--弹窗html-->
<div id="fastLogin_box" style="display:none">
<div class="mask"></div>
<div class="popbox2" style="z-index:999">
<form action="/auth/login/" method="post"  onsubmit="return fastLogin();" name="loginForm" id="loginForm">
<div style="float: left;width: 298px;height: 518px;position: relative;">
		<div class="uemail">
			<input id="user_name" type="text" maxlength="60" name="user_name" class="text">
			<input type="hidden" value="member" name="utype"/>
		</div>
		
		<div class="upass">
			<input class="text" type="password" maxlength="60" name="password" id="pwd"/>
		</div>
		
		<div class="rm-login-status">
			<label><input value="1" name="auto_login" type="checkbox" class="checkbox"> 记住登录</label>
		</div>
		
		<div class="error-msg">
			<p class="red" id="msg_box">&nbsp;</p>
		</div>
		
		<div class="fergot-pass">
			<a href="/auth/get-password">忘记密码</a>
		</div>
		
		<div class="verify">
			<input style="width:90px;height:28px;" name="verifyCode"  maxlength="5" id="vcode" msg="验证码不能为空" type="text" onkeyup="parseUpperCase(this)" style="width:100px"   />
			<img id="verify_img" class="auth-image" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/auth/auth-image/space/shopLogin/code/<?php echo time(); ?>
" alt="verifyCode" border="0" onclick="change_verify('verify_img','shopLogin');" style="cursor: pointer;" title="点击更换验证码" />
	  </div>
		
		
		<div class="btn-submit">
			<input name="submitted"  type="submit" class="pic" value=""/>	
		</div>	
		
		<div class="btn-regist">
			<a href="/reg.html">注册新用户</a>
		</div>		
		
	</div>	
<div class="fr" style="position: relative;">
		<a href="/flow/fast-track-buy" class="quick">快速通道</a>
		<div id="closePop" onclick="$('#fastLogin_box').fadeOut();" title="关闭"></div>
		<div class="trust-login">
			<a href="/auth/qqlogin" target="_blank" title="腾讯QQ"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/auth/qq_logo.jpg" alt="腾讯QQ" /></a>
			<a href="https://api.weibo.com/oauth2/authorize?client_id=1862192494&redirect_uri=http%3A%2F%2Fwww.1jiankang.com%2Fauth%2Fweibocallback&response_type=code" target="_blank" title="新浪微博"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/auth/sina_logo.jpg" alt="新浪微博"/></a>
			<a href="/auth/kaixinlogin" target="_blank" title="开心网" ><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/auth/kaixin_logo.jpg" alt="开心网"/></a>
			<a href="https://oauth.taobao.com/authorize?response_type=code&client_id=21573157&redirect_uri=http%3A%2F%2Fwww.1jiankang.com%2Fauth%2Ftaobaocallback&state=1" target="_blank" title="淘宝网" ><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/auth/taobao_logo.jpg" alt="淘宝网" /></a>
			<a href="http://reg.163.com/open/oauth2/authorize.do?client_id=7449660002&redirect_uri=http%3A%2F%2Fwww.1jiankang.com%2Fauth%2Fwangyicallback&response_type=code" target="_blank" title="网易网"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/auth/wangyi_logo.jpg" alt="网易网" /></a>
		</div>
</div>
</form>	
</div>
</div>