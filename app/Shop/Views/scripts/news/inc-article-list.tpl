<div class="articleList">
	<ul>
		{{foreach from=$list item=v key=k}}
		<li>
			<h2><a target="_blank" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">{{$v.title}}</a></h2>
			<p>
				<span>{{$v.abstract|cn_truncate:200}}</span><em>【<a target="_blank" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">详情</a>】</em><i>{{$v.add_time}}</i>
			</p>
		</li>
		{{/foreach}}
	</ul>
	<div class="pagenav">
		{{$pagenav}}
	</div>
</div>
