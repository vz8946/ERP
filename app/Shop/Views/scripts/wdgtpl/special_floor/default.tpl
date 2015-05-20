{{foreach from=$goods item=v key=k}}
<div class="topic-goods-item" {{if ($k+1)%4 == 0}}style="margin-right: 0px;"{{/if}}>
	<div  class="imgs">
		{{if $v.url == '#'}}
			<img alt="{{$v.title}}" height="175" src="{{$imgBaseUrl}}/{{$v.img}}" />
		{{else}}
		<a href="{{if $v.url}}{{$v.url}}{{else}}{{$v.goods_url}}{{/if}}" target="_blank">
			{{html type="img" src=$v.img alt=$v.title w=175 h=175}}
		</a>
		{{/if}}
	</div>
	
	<div class="info">
		
		<div class="sp"><span class="c1 fs1 fb">{{if $certain_price.0.title}}{{$certain_price.0.title}}{{else}}{{$v.price|default:$v.goods.price}} {{/if}}</span> ￥</div>
		<div class="ga c1" style="height: 18px;">{{$v.goods_alt|default:$v.goods.goods_alt}}</div>
		<div class="op c3">原价：<span class="fl1">{{$v.market_price|default:$v.goods.market_price}}￥</span>&nbsp;&nbsp;&nbsp;&nbsp;节省：<span class="c2">{{$v.disprice}}</span>￥</div>
		
		<div class="gt">
			{{if $v.url == '#'}}
				{{$v.goods_name|default:$v.goods.goods_name}}
			{{else}}
				<a href="{{$v.url|default:$v.goods_url}}" target="_blank">{{$v.goods_name|default:$v.goods.goods_name}}</a>
			{{/if}}
		</div>
		
		<div class="opt"><a class="btn" href="javascript:void(0);"
			{{if $v.url != '#'}}onclick="addCart('{{$v.goods.goods_sn}}')"{{/if}}
		>立即购买</a></div>
		
	</div>
</div>
{{/foreach}}

<div style="clear: both;"></div>

