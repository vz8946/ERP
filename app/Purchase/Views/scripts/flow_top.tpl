<div class="topnav">
<div class="wbox clearfix">
  <p style="padding-left: 0px;" class="login_info fl">
	<span style="padding-left: 0px;" class="l welcome" id="user_login_span">
	<span class="fs13">
	<span class="l">
	{{assign  var="h" value=$smarty.now|date_format:'%H'}}
	{{if $h<9}}早上好{{elseif $h<12}}上午好 {{elseif $h<13}}中午好 {{elseif $h<17}}下午好{{else}}晚上好{{/if}},欢迎来到垦丰商城 ! 
	</span>
	
	{{if $auth}}
	<a href="/member" style="padding:0 4px;" id="glob-user">{{$auth.user_name}}</a>  
	<a class="blue" href="/logout.html">[退出]</a> <!--<a href="/member">我的帐户</a>-->
	{{else}}
    <a id="glob-user" style="padding:0 4px;" class="blue" href="/login.html">[请登录]</a>
	<a style="padding:0 4px;" class="orange" href="/reg.html">[免费注册]</a>
   {{/if}}	
	</span>	
	</span>
</p>
<div class="topmenu fr">
	<ul>
	    <li class="">
			<a rel="nofollow" target="_blank" href="/help">帮助中心&nbsp;</a>
		</li>
		<li class="kefu">
			<a href="http://vipwebchat.tq.cn/pageinfo.jsp?version=vip&amp;admiuin=8989693&amp;ltype=0&amp;iscallback=1&amp;page_templete_id=21608&amp;is_message_sms=0&amp;is_send_mail=0&amp;action=acd&amp;acd=1&amp;type_code=1" target="_blank" rel="nofollow"><s></s>在线客服</a>
		</li>
	</ul>
</div>
</div>
</div>