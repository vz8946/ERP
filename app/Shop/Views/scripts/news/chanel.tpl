{{include file="news/inc-header.tpl"}}
<div class="content">
				<!--
	<div class="ad01">
		{{html type="wdt" id="news_cat_adv1"}}
	</div>
			-->
	<div style="padding-top:10px;">
		<a href="/">垦丰商城 </a>&gt;
		<a href="/news">资讯首页</a>&gt;
		<a href="/chanel-{{$tag.name}}">{{$tag.title}}</a>
		
	</div>
	<div class="sepline"></div>
	<div class="Container">
		
		{{include file="news/inc-article-list.tpl"}}

		<div class="sidebar">
			{{if $list_prmt_article}}
			<div class="title_hot">
				<ul>
					{{foreach from=$list_prmt_article item=v key=k}}
					<li>
						<a target="_blank" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">{{$v.title}}</a>
					</li>
					{{/foreach}}
				</ul>
			</div>
			{{/if}}
			<!--
			<div class="ad02">
				<a target="_blank" href="#"><img src="/newstatic/images/ad02.jpg" width="276" height="200" /></a>
			</div>
				-->
			{{include file="news/inc-goods-list.tpl"}}
		</div>
	</div>
</div>
{{include file="news/inc-footer.tpl"}}
