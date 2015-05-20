<div class="location">
	<a href="/"> 首页</a> &gt; 查看所有分类
</div>

<div class="moreclass">
	<div class="moreclass_tab">
		<a href="/sitemap.html"><span>查看所有分类</span></a>
		<a class="cur" href="/sitemap-brand.html"><span>查看所有品牌</span></a>
		<p class="clear"></p>
	</div>
	<div class="moreclass_box">
		{{foreach from=$list_brand item=v key=k}}
		<div class="brander">
			<div class="country"></div>
			<h2>{{$k}}</h2>
			<div style="padding: 10px 0px;">
				{{foreach from=$v item=vv key=kk}}
				<a href="/b-{{$vv.as_name}}">
				<img style="border: 1px solid #eee;padding:3px;display: inline-block;margin-right: 10px;margin-bottom: 10px;"
					width="120" height="51" src="{{$imgBaseUrl}}/{{$vv.small_logo}}"/>
				</a>
				{{/foreach}}
			</div>
		</div>
		{{/foreach}}
	</div>
</div>