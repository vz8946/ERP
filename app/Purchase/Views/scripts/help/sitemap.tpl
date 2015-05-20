<div class="location">
	<a href="/"> 首页</a> &gt; 查看所有分类
</div>

<div class="moreclass">
	<div class="moreclass_tab">
		<a class="cur" href="/sitemap.html" id="showSort"><span>查看所有分类</span></a>
		<a href="/sitemap-brand.html" id="showBrand"><span>查看所有品牌</span></a>
		<p class="clear"></p>
	</div>

	<div class="moreclass_box">
		{{foreach from=$tree_cat item=v key=k}}
		<div class="list">
			<div class="title">
				<h2><a target="_blank" href="/gallery-{{$v.cat_id}}.html">{{$v.cat_name}}</a></h2>
			</div>
			<div class="cont">
				{{foreach from=$v.sub item=vv key=kk}}
				<dl>
					<dt>
						<a target="_blank" href="/gallery-{{$vv.cat_id}}.html">{{$vv.cat_name}}</a>
					</dt>
					<dd>
						{{foreach name=vv_sub from=$vv.sub item=vvv key=kkk}}
						<a target="_blank" href="/gallery-{{$vvv.cat_id}}.html">{{$vvv.cat_name}}</a>
						{{if $smarty.foreach.vv_sub.last != '1'}}
						<span> | </span>
						{{/if}}
						{{/foreach}}
					</dd>
				</dl>
				{{/foreach}}
			</div>
		</div>
		{{/foreach}}
	</div>
</div>