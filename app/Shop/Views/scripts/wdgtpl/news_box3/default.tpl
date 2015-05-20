<div class="box">
	<div class="b-t">
		<div class="b-t-l">{{$title}}</div>
	</div>
	<div class="b-c tw">
		{{foreach from=$news item=v key=k}}
		<div class="tw-item">
			<div class="fl mgr1"><img width="100" src="{{$imgBaseUrl}}/{{$v.img}}"/></div>
			<div class="pdb1"><a class="c4 fs1" href="#">{{$v.title}}</a></div>
			<p>{{$v.memo}}</p>
		</div>
		{{/foreach}}
	</div>
</div>
