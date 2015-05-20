<div class="title">
	<div class="t-l c7 fs2 fl">{{$title}}</div>
	<div class="t-r fr">
		{{foreach from=$links item=v key=k}}
		{{if $k != 0}}<span>|</span>{{/if}}
		<a href="{{$v.url}}">{{$v.title}}</a>
		{{/foreach}}
	</div>
</div>
