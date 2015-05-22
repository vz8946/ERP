{{foreach from=$goods item=v key=k}}
<div class="topic-goods-item" {{if ($k+1)%2 == 0}}style="margin-right: 0px;"{{/if}}>
	<div  class="imgs" style="float: left;">
		<table style="width: 100%;height: 100%;">
			<tr>
				<td>
					{{if $v.url == '#'}}
						<img alt="{{$v.title}}" height="230" src="{{$imgBaseUrl}}/{{$v.img}}" />
					{{else}}
					<a href="{{$v.url|default:$v.goods_url}}" target="_blank">
						<img alt="{{$v.title}}" height="230" src="{{$imgBaseUrl}}/{{$v.img}}" />
					</a>
					{{/if}}
				</td>
			</tr>
		</table>
	</div>
	<div class="info">
		<div class="sp"><span class="c1 fs1 fb">{{if $certain_price.0.title}}{{$certain_price.0.title}}{{else}}{{$v.goods.price}} {{/if}}</span> ￥</div>
		<div style="height: 15px;overflow: hidden;"></div>
		<div class="op c3">原价：<span class="fl1">{{$v.goods.market_price}}￥</span>&nbsp;&nbsp;&nbsp;&nbsp;节省：<span class="c2">{{$v.goods.disprice}}</span>￥</div>
		<div class="gt">
			{{if $v.url == '#'}}
				{{$v.goods_name|default:$v.goods.goods_name}}
			{{else}}
				<a href="{{$v.url|default:$v.goods_url}}" target="_blank">{{$v.goods_name|default:$v.goods.goods_name}}</a>
			{{/if}}
		</div>
		<div class="ga c1" style="height: 18px;">{{$v.goods_alt|default:$v.goods.goods_alt}}</div>		
		<div class="opt"><a class="btn" href="javascript:void(0);"
			{{if $v.url != '#'}}onclick="addCart('{{$v.goods.goods_sn}}')"{{/if}}
		>立即购买</a></div>
		
	</div>
</div>
{{/foreach}}
<div style="clear: both;"></div>