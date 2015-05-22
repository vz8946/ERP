<link rel="stylesheet" type="text/css" href="/styles/shop/help.css"/>
<div class="main mar">
<div class="pos"><a href="#">首页</a> >> <a href="#">帮助中心</a></div>
<div class="left">
<dl>
<dt><p>{{$info.cat_name}}</p></dt>
<dd>
{{foreach from=$menu item=item}}
<a href="/about-{{$item.article_id}}.html" target="_blank" >{{$item.title}}</a>
{{/foreach}}

</dd></dl>

</div><!--left结束-->
<div class="rig">
<dl class="list">
	<h2 >{{$info.title}}</h2>
	
	<div >{{$info.content}}</div>
</dl>
</div><!--rig 结束-->
<div class="clear"></div>
</div><!--main结束-->