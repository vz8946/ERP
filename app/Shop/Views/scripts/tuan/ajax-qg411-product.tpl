<ul>
{{foreach from=$list_tuan item=v key=k}}
<li>
	<div style="width: 316px;height: 315px;overflow: hidden;">
		<a target="_blank" href="{{$v.url}}" title="{{$v.title}}">
			{{html type="img" src=$v.tuan_goods.img1|default:$v.goods.goods_img 
				w=316 h=315 alt=$v.title}}</a>
	</div>
	<p>
		<a target="_blank" href="{{$v.url}}" title="{{$v.title}}">{{$v.title}}</a>
	</p>
	<img src="/images/shop/tuan/qg411/{{$v.status_bg}}" width="320" height="52" />
	<div class="num">
		仅限
		<br />
		<em>{{$v.max_count}}件</em>
	</div>
	<div class="price">
		原价：￥{{$v.goods.price}}
		<br />
		<em>抢购价：￥{{$v.price}}</em>
	</div>
	<a style="display: block;position: absolute;width:140px;height: 52px;right:0px;" target="_blank" href="{{$v.url}}">&nbsp;</a>
</li>
{{/foreach}}
</ul>
