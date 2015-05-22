{{include file="news/inc-header.tpl"}}
<div class="content">

	{{if $cat.adv1}}
	<div class="ad01">
		{{html type="wdt" id=$cat.adv1}}
	</div>
	{{/if}}

	<div class="breadcrumb">
		<a href="/">垦丰商城</a> &gt;
		<a href="/news">资讯首页</a> &gt;
		<a href="/news-{{$cat.as_name}}">{{$cat.cat_name}}</a> &gt;
		{{$article.title}}
	</div>
	<div class="Container">
		<div class="article_content">
			<h1>{{$article.title}}</h1>
			<div style="text-align: center;color:#555;padding-bottom: 10px;">时间：{{$article.add_time}}</div>
			<div class="daodu">
				<b>[导读]</b>
				{{$article.abstract}}
			</div>
			<p>
				<b>正文内容</b>
				<br />
				{{$article.content}}
			</p>
			<div style="height: 30px;overflow: hidden;"></div>
			<div style="height: 20px;overflow: hidden;">
				{{if $prev_article}}
				<span class="fl"><a target="_blank" href="/news-{{$prev_article.as_name}}/detail-{{$prev_article.article_id}}.html">&lt;&lt;上一篇</a></span>
				{{else}}
				<span class="fl">&lt;&lt;上一篇</span>
				{{/if}}
				
				{{if $next_article}}
				<span class="fr"><a target="_blank" href="/news-{{$next_article.as_name}}/detail-{{$next_article.article_id}}.html">下一篇&gt;&gt;</a></span>
				{{else}}
				<span class="fr">下一篇&gt;&gt;</span>
				{{/if}}
			</div>
			<div style="height: 30px;overflow: hidden;"></div>
			{{if $list_link_article}}
			<div class="links-article">
				<h2>相关文章</h2>
				<div style="height: 5px;overflow: hidden;"></div>
				<table width="100%">
					{{foreach from=$list_link_article item=v key=k}}
					<tr>
						<th><a target="_blank" href="/news-{{$cat.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">{{$v.title}}</a></th>
						<td width="150" align="right">{{$v.add_time}}</td>
					</tr>
					{{/foreach}}
				</table>
			</div>
			{{/if}}
		</div>
		
		
		<div class="sidebar">
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
