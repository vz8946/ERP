{{include file="news/inc-header.tpl"}}
<div class="content">
	{{if $cat.adv1}}
	<div class="ad01">
	{{html type="wdt" id=$cat.adv1}}
	</div>
	{{/if}}

	<div style="padding-top: 10px;">
		<a href="/">垦丰商城 </a>
		&gt;
		<a href="/news">资讯首页 </a>
		&gt;
		<a href="/news-{{$cat.as_name}}">{{$cat.cat_name}}</a>
	</div>
	<div class="sepline"></div>
	<div class="Container">
		{{include file="news/inc-article-list.tpl"}}

		<div class="sidebar">
			<div class="title_hot">
				<ul>
					{{foreach from=$list_prmt_article item=v key=k}}
					<li>
						<a target="_blank" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">{{$v.title}}</a>
					</li>
					{{/foreach}}
				</ul>
			</div>
			
			{{if $cat.adv2}}
			<div class="ad02">
				{{html type="wdt" id=$cat.adv2}}
			</div>
			{{/if}}
			
			{{include file="news/inc-goods-list.tpl"}}
			
		</div>
		
	</div>
</div>
{{include file="news/inc-footer.tpl"}}
