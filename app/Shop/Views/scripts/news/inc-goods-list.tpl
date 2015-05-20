{{if $list_goods}}
<div class="rank">
	<h3>相关产品推荐</h3>
	<ul>
		{{foreach from=$list_goods item=v key=k}}
		<li>
			<div class="product_img">
				<a target="_blank" href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html"><img src="/{{$v.goods_img}}" width="99" height="99" /></a>
			</div>
			<p>
				<a target="_blank" href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html">{{$v.goods_name}}</a>
				<br />
				<em>￥{{$v.price}}</em>
			</p>
		</li>
		{{/foreach}}
	</ul>
</div>
{{/if}}
