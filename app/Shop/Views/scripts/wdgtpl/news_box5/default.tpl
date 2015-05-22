<ul class="article-list">
	{{foreach from=$news item=v key=k}}
	<a href="{{$v.url|default:$v.news_url}}">{{$v.title}}</a>
	{{/foreach}}
</ul>
