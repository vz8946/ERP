<ul class="index-goods-list1">
	{{foreach from=$goods item=v key=k}}
	{{if $k == 0}}
	<li style="width: 221px;">
	{{elseif $k == 3}}
	<li style="width: 222px;margin-right: 0px;">
	{{else}}
	<li style="width: 224px;">
	{{/if}}
		<div class="pi">
			{{if $v.url == '#'}}
				{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
			{{else}}
			<a href="{{if $v.url}}{{$v.url}}{{else}}{{$v.goods_url}}{{/if}}" target="_blank">
				{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
			</a>
			{{/if}}
		</div>
		<div class="pa fs1 fb c1">
			{{$v.goods_alt|default:$v.goods.goods_alt}}
		</div>
		<div class="pt">
			{{if $v.url == '#'}}
				{{$v.goods_name|default:$v.goods.goods_name}}
			{{else}}
				<a href="{{$v.url|default:$v.goods_url}}" target="_blank">{{$v.goods_name|default:$v.goods.goods_name}}</a>
			{{/if}}
		</div>
		<div class="pp">
			<span class="pmp fl">￥{{$v.market_price|default:$v.goods.market_price}}</span>
			<span class="prp fr c2 fs3">￥{{$v.price|default:$v.goods.price}}</span>
		</div>
	</li>
	{{/foreach}}
</ul>
