<div class="today-focus">
	<div class="t c7 fs2" style="border-bottom:1px dashed #eee;padding-bottom: 5px;">{{$title}}</div>
	<div class="c">
		<ul>
			{{foreach from=$news1 item=v key=k}}
			{{if $k == 0}}
			<ol><a href="{{$v.url|default:$v.news_url}}">{{$v.title}}</a></ol>
			{{else}}
			<li><a href="{{$v.url|default:$v.news_url}}">{{$v.title}}</a></li>
			{{/if}}
			{{/foreach}}
		</ul>
		<div style="height: 20px;overflow: hidden;"></div>
		<ul>
			{{foreach from=$news2 item=v key=k}}
			{{if $k == 0}}
			<ol><a href="{{$v.url|default:$v.news_url}}">{{$v.title}}</a></ol>
			{{else}}
			<li><a href="{{$v.url|default:$v.news_url}}">{{$v.title}}</a></li>
			{{/if}}
			{{/foreach}}
		</ul>
	</div>
</div>
