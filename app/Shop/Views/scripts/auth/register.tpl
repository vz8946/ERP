{{include file="header.tpl"}}
<link href="/Public/css/register.css" rel="stylesheet">
<div class="container find_b">
                <div class="regSuccess">
                    <div class="h3_title">
                        <h3 class="t6">注册新用户</h3>
                    </div>
                   {{if $is_success eq "YES"}}
                   		<h1></h1>
                    <p>• 您的垦丰商城用户名为 {{$username}}</p>
                   {{else}}
						<p><img src="{{$imgBaseUrl}}/Public/img/error_icon.gif" style="margin-left:40px;"/></p>
						<p>•错误信息：<span style="color:blue;"> {{$message}}</span></p>
					{{/if}}
                    
					<p>• 页面将在 <span id="totalSecond" style="color:red;font-size:16px;">5</span> 秒钟后跳转至上一次操作页面。</p>


                    <div class="btnBox" style="position:relative;">
                        <a href="/"  ><span  class="btns w78px" style="background: url(/Public/css/../img/register_btn1.png) no-repeat scroll 0 0 transparent;color: #FFFFFF; display: inline-block;font-size: 14px;font-weight: bold;height: 30px;line-height: 30px;padding-top: 0;text-align: center; width:111px;cursor:pointer;">返回首页</span></a>
                        <a href="/member" ><span  class="btns w78px" style="background: url(/Public/css/../img/register_btn1.png) no-repeat scroll 0 0 transparent;color: #FFFFFF; display: inline-block;font-size: 14px;font-weight: bold;height: 30px;line-height: 30px;padding-top: 0;text-align: center; width:111px;cursor:pointer;">进入会员专区</span></a>
                    </div>
                </div>
</div>
<script language="JavaScript" type="text/javascript">
{{if $is_success eq "YES"}}
delayURL("{{$goto}}");
{{else}}
delayURL("{{$refer}}");
{{/if}}
function delayURL(url) {
		var delay = document.getElementById("totalSecond").innerHTML;
		if(delay > 0) {
			delay--;
			document.getElementById("totalSecond").innerHTML = delay;
		} else {
			window.top.location.href = url;
		}
		setTimeout("delayURL('" + url + "')", 1000);
}
</script>
{{include file="footer.tpl"}}