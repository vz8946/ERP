<div class="box">
	<div class="b-t">
		<div class="b-t-l">{{$title}}</div>
		<div class="b-t-r">
			<a href="{{$more_url}}">{{$more_title}}</a>
		</div>
	</div>
	<div class="b-c">
		<div>
			<div class="fl mgr1">
				<div style="width: 100px;height: 110px;overflow:hidden; 
					text-align:center;
					border: 1px solid #eee;position: relative;top:5px;">
					<a href="#"><img width="100" src="{{$imgBaseUrl}}/{{$news.0.img}}"/></a>
			</div></div>
			<div class="pdb1"><a class="c4 fs1" href="#">{{$news.0.title}}</a></div>
			<p style="padding:0px 5px;">{{$news.0.memo}}</p>
		</div>
		<ul class="clear">
			{{foreach from=$news item=v key=k}}
			{{if $k > 0}}
			<li><a href="#">{{$v.title}}</a></li>
			{{/if}}
			{{/foreach}}
		</ul>
	</div>
</div>		
