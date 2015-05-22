<div class="box">
	<div class="b-t">
		<div class="b-t-l">{{$title}}</div>
		<div class="b-t-r">
			<a href="{{$more_url}}">{{$more_title}}</a>
		</div>
	</div>
	<div class="b-c">
		<ul class="clear">
			{{foreach from=$news item=v key=k}}
			<li>
				<a href="#">{{$v.title}}</a>
			</li>
			{{/foreach}}
		</ul>
	</div>

</div>
