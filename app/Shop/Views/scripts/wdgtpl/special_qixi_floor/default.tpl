{{foreach from=$goods item=v key=k}}
<div class="box" {{if $k%3 == 0}}style="margin-left: 0px;"{{/if}}>
	<div width="324" height="247" style="width: 324px;height: 274px;overflow: hidden;text-align: center;">
		{{if $v.url == '#'}}
			<img alt="{{$v.title}}" style="display: inline-block;margin: 0px auto;" height="272" src="{{$imgBaseUrl}}/{{$v.img}}" />
		{{else}}
		<a href="{{$v.url|default:$v.goods_url}}" target="_blank" style="display: inline-block;margin: 0px auto;">
			<img alt="{{$v.title}}" height="272" src="{{$imgBaseUrl}}/{{$v.img}}" />
		</a>
		{{/if}}
	</div>
	<div class="text-1">
		{{if $v.url == '#'}}
			{{$v.goods_name|default:$v.goods.goods_name}}
		{{else}}
			<a href="{{$v.url|default:$v.goods_url}}" target="_blank">{{$v.goods_name|default:$v.goods.goods_name}}</a>
		{{/if}}
	</div>
	<div class="text-1">{{$v.goods_alt|default:$v.goods.goods_alt}}</div>
	<div style="padding-left: 10px; height: 39px; line-height: 39px;">
		<span class="text-2">
			{{if $tip_price_name.0.title}}
				{{$tip_price_name.0.title}}
			{{else}}
				垦丰价
			{{/if}}
			：¥</span> <span class="text-3"
			{{if $v.url == '#'}}style="color:gray;"{{/if}}
			>
				{{if $certain_price.0.title}}{{$certain_price.0.title}}{{else}}{{$v.goods.price}} {{/if}}
			</span> <span
			style="float: right; padding-top: 6px;"><a
			href="javascript:void(0);" 
			{{if $v.url != '#'}}onclick="addCart('{{$v.goods.goods_sn}}')"{{/if}}
			><img
				src="{{$imgBaseUrl}}/images/special/qixi/buy-button.png" width="105" height="27" /></a></span>
	</div>
	<div
		style="width: 324px; height: 54px; background: url(/images/special/qixi/sale-infor.png) no-repeat;"
		class="text-4">
		<ul>
			<li class="sale-infor">￥{{$v.goods.market_price}}</li>
			<li class="sale-infor">{{$v.goods.saleoff}}</li>
			<li class="sale-infor">￥{{$v.goods.disprice}}</li>
		</ul>
	</div>
</div>
{{/foreach}}
<div style="clear: both;"></div>

