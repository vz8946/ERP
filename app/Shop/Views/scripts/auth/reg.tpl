<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>用户注册-垦丰商城 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="image/x-icon" href="{{$_static_}}/images/home.ico" rel="Shortcut Icon">
<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,login.css&v={{$sys_version}}.css" rel="stylesheet" />
<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,common.js,validform.js,passwordStrength.js,login.js&v={{$sys_version}}.js" type="text/jscript"></script>
</head>
<body>
<form action="/auth/register" method="post" name="formRegister" id="formRegister" >
{{if $error}}
<div class="red">
	{{$error}}
</div>
{{/if}}
{{if $goto}}
<input type="hidden" name="goto" value="{{$goto}}" />
{{/if}}
<div class="main">
  <div class="header"><img src="{{$_static_}}/images/login/reg_top.jpg" usemap="#Map" width="990" height="93" />
  <map id="Map" name="Map">
      <area href="/" coords="3,3,228,98" shape="rect"></area>
   </map>
  </div>
  <div class="content reg">
    <div class="fl">
      <div class="title"> <img src="{{$_static_}}/images/login/title_reg.jpg" width="128" height="37" /> <span>我已经注册，现在就去 <a href="/login.html">登陆</a> </span> </div>
      <div class="form">
        
        <p class="label">
          <label>电子邮箱：</label>
          <input name="user_name" id="user_name" value="" maxlength="60"  datatype="e" ajaxurl="/auth/check/" sucmsg="邮箱验证通过！" nullmsg="请输入邮箱！"  errormsg="输入有效的Email地址！"  type="text" />
          <span class="Validform_checktip">输入有效的Email地址</span> 
          <ul id="result" class="sea"></ul>        
        </p>
       
        <p class="label">
          <label>密  码：</label>
          <input type="password" class="text" id="pw" name="password"  value="" dataType="*6-16"  datatype="*6-16" nullmsg="请设置密码！" errormsg="密码范围在6~16位之间！" plugin="passwordStrength" />
           <span class="Validform_checktip">密码至少6个字符,最多16个字符！</span>    
         </p>
         
         <p class="label">
           <label>&nbsp;</label>
         <div class="passwordStrength"><b>强度：</b><span>弱</span><span>中</span><span class="last">强</span></div>   
        </p> 
        
        <p class="label">
          <label>确认密码：</label>
          <input type="password" name="confirm_password"  errormsg="您两次输入的账号密码不一致！" value="" nullmsg="请再输入一次密码！" recheck="password"  datatype="*"  id="cpassword"  type="text" />
          <span class="Validform_checktip">请确认密码</span>
        </p>
        <p class="label code">
          <label>验证码：</label>
          <input ajaxurl="/auth/check-reg-code/" nullmsg="请输入验证码！" errormsg="输入右侧图片中的字符！"  datatype="s1-5"  name="verifyCode" id="verifyCode"  maxlength="5"  type="text" />
          <img src="/auth/auth-image/space/shopRegister/code/{{$smarty.now}}" onclick="change_verify('verify_img','shopRegister');" id="verify_img"   width="64" height="24" /><a href="javascript:;" onclick="change_verify('verify_img','shopRegister');">换一个</a>
           <span class="Validform_checktip">请输入验证码</span>
          </p>
        <p class="read">
          	<input type="checkbox" name="agreement"  nullmsg="请勾选用户隐私条款！"  datatype="clause"   id="agreement" class="checkbox" checked="checked"/>
                              我已阅读 <span>[<a href="/help/detail-20.html" target="_blank">垦丰电商用户隐私条款</a>]</span>
           <span class="Validform_checktip"></span>                 
        </p>
        <p class="btn">
         <input type="image" src="{{$_static_}}/images/login/btn_reg.jpg" alt="注册" /></p>
      </div>
      <div class="cooperation"> 您也可以用合作伙伴账号登陆：
           <a href="/auth/qqlogin"><img src="{{$_static_}}/images/login/i_qq.jpg" width="21" height="20" /></a>
            <a href="https://oauth.taobao.com/authorize?response_type=code&client_id=21573157&redirect_uri=http%3A%2F%2Fwww.1jiankang.com%2Fauth%2Ftaobaocallback&state=1"><img src="{{$_static_}}/images/login/i_taobao.jpg" width="19" height="20" /></a>
            <a href="https://api.weibo.com/oauth2/authorize?client_id=1862192494&redirect_uri=http%3A%2F%2Fwww.1jiankang.com%2Fauth%2Fweibocallback&response_type=code"><img src="{{$_static_}}/images/login/i_weibo.jpg" width="22" height="20" /></a>
            <a href="/auth/kaixinlogin"><img src="{{$_static_}}/images/login/i_kaixin.jpg" width="21" height="20" /></a>
            <a href="http://reg.163.com/open/oauth2/authorize.do?client_id=7449660002&redirect_uri=http%3A%2F%2Fwww.1jiankang.com%2Fauth%2Fwangyicallback&response_type=code"><img src="{{$_static_}}/images/login/i_wangyi.jpg" width="15" height="18" /></a> 
            
      </div>
    </div>
    <div class="fr">
   	  <h3>成为垦丰会员后，您将：</h3>
        <ul>
        	<li><i><img src="{{$_static_}}/images/login/ji.jpg" width="30" height="30" /></i>享受购物送积分，累计可抵现</li>
            <li><i><img src="{{$_static_}}/images/login/vip.jpg" width="30" height="30" /></i><span>享受高级会员专属权益<br />（如购物折扣、会员日、专属客服等）</span></li>
            <li><i><img src="{{$_static_}}/images/login/search.jpg" width="30" height="30" /></i>随时查询和跟踪订单状态</li>
            <li><i><img src="{{$_static_}}/images/login/love.jpg" width="30" height="30" /></i>收藏商品、查看留言与回复</li>
            
        </ul>
    </div>
  </div>
</div>
</form>
<div class="wbox footer" style="padding-top: 10px;">  
<div class="menu">
     <a href="/help/detail-19.html">关于我们</a> |
     <a href="/help/detail-18.html">联系客服</a>|
     <a href="/help/detail-22.html">诚聘英才</a>|
     <a href="/help/detail-23.html">商务合作</a>
   </div>
  <div class="copyright">垦丰商城 All Rights Reserved 2014 <br>
   ICP证:<a href="#" target="_blank" rel="nofollow"> 
       沪ICP备10200022号-3 </a><br>
  </div>
 <small class="footer_pic_link">
      <img src="{{$_static_}}/images/footer_pic1.jpg">
      <script src="http://kxlogo.knet.cn/seallogo.dll?sn=e13090231010042247zyhm000000&amp;size=3"></script>
      <a href="http://www.sgs.gov.cn/lz/licenseLink.do?method=licenceView&amp;entyId=20121218094238892" target="_blank" rel="nofollow"><img src="{{$_static_}}/images/280053.gif"></a>   
   </small>
</div>
<script type="text/javascript">
$(function(){	
    $("#formRegister").Validform({
  	   tiptype:function(msg,o,cssctl){
  		  if(!o.obj.is("form")){
  			var objtip=o.obj.siblings(".Validform_checktip"); 		
 			cssctl(objtip,o.type);
 			objtip.text(msg);
  		  }else{
  			 var objtip=o.obj.find("#msg_tips");
 			 cssctl(objtip,o.type);
 			 objtip.text(msg);
  		  }
  		},
  		datatype:{ //传入自定义datatype类型【方式二】;
		 "clause":function(gets,obj,curform,regxp){
				var need=1,
				numselected=curform.find("input[name='"+obj.attr("name")+"']:checked").length;
				return  numselected >= need ? true : "请勾选用户隐私条款";
		}},			
  		usePlugin:{passwordstrength:{minLen:6,maxLen:16}}
  	});  
    
    $('#user_name').bind('keyup',function(e){userNameSuggest(this,e);});
    $('#verifyCode').bind('keyup',function(){parseUpperCase(this)});
    
    //Tab 键close tips  div
    document.onkeydown=function(event){
    	e = event ? event :(window.event ? window.event : null); 
	    if(e.keyCode==9)
	    {
	    	close_suggest();
	    }
    }    
    $('body').click(function (e) {
        var drag = $("#result"),
            dragel = $("#result")[0],
            target = e.target;
        if (dragel !== target && !$.contains(dragel, target)) {
            drag.hide();
        }
    });     
});
</script>
</body>
</html>