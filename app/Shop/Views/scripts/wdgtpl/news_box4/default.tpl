<div class="box2">
	<div class="b-t">
		<div class="b-t-l">{{$title}}</div>
		<div class="b-t-r">
			<a href="{{$more_url}}">{{$more_title}}</a>
		</div>
	</div>
	<div class="b-c">
		<ul>
			{{foreach from=$news item=v key=k}}
			{{if $k == 0}}
			<ol>
				<a href="{{$v.url|default:$v.news_url}}">{{$v.title}}</a>
			</ol>
			{{else}}
			<li><a href="{{$v.url|default:$v.news_url}}">{{$v.title}}</a></li>
			{{/if}}
			{{/foreach}}
		</ul>
		<div class="img">
			<a href="{{$img.0.url}}"><img width="120" height="100" src="{{$imgBaseUrl}}/{{$img.0.img}}"/></a>
		</div>
	</div>
</div>