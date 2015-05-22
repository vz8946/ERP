 {{if $groupGoodsAData}}
<div class="group-goods box">
	<div class="lfoat b-t">优惠套装</div>
	<div id="gg-tab" class="lfloat b-c tab">
		<ul class="tab-h">
			{{foreach from=$groupGoodsAData item=v key=k}}
			<li>优惠套装{{$k+1}}</li> {{/foreach}}
			<div style="clear: both;"></div>
		</ul>
		<div class="lfoat tab-c">
			{{foreach from=$groupGoodsAData item=v key=k}}
			<div class="lfloat group-item" {{if $k > 0}}style="display:none"{{/if}}>
				<div class="goods-item">
					<div>
						<a target="_blank" href="/goods-{{$data.goods_id}}.html"><img width="120" src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_380_380.'}}" /></a>
					</div>
					<div>
						<a target="_blank" href="/goods-{{$data.goods_id}}.html" title="{{$data.goods_name}}">{{$data.goods_name|cn_truncate:60:''}}</a>
					</div>
				</div>
				
				<div style="float:left;width:360px;{{if $v.item_len > 2}}overflow-x:scroll;{{/if}}">
				<div style="width:{{math equation="x * y" x=180 y=$v.item_len}}px">
				{{foreach from=$v.group_goods_config item=vv key=kk}} 
				{{if $vv.goods_id != $data.goods_id}}
				<div class="lfloat goods-item goods-link">
					<div>
						<a target="_blank" href="/goods-{{$vv.goods_id}}.html"><img width="120" src="{{$imgBaseUrl}}/{{$vv.goods_img|replace:'.':'_380_380.'}}" /></a>
					</div>
					<div>
						<a target="_blank" href="/goods-{{$vv.goods_id}}.html" title="{{$vv.goods_name}}">{{$vv.goods_name|cn_truncate:60:''}}</a>
					</div>
				</div>	
				{{/if}} {{/foreach}}
				</div>
</div>
				<div class="group-info">
					<span class="gn"><a target="_blank"
						href="/groupgoods-{{$v.group_id}}.html">{{$v.group_goods_name}}</a></span><br />
					套 装 价：￥ {{$v.group_price}}<br />
					原&nbsp;&nbsp;&nbsp;&nbsp;价：￥ {{$v.group_market_price}}<br />
					立即节省：￥ {{$v.group_market_price-$v.group_price}}<br />
					<div style="height: 10px; overflow: hidden;"></div>
					<a id="btn-gg-buy" class="btn-gg-bug"
						onclick="addGroupCart({{$v.group_id}},'buy',1);"
						href="javascript:void(0);">购买套餐</a>
				</div>
			</div>
			{{/foreach}}
		</div>
	</div>
	<div style="clear: both;"></div>
</div>
<script>	
	$(function(){
		$('#gg-tab').tab();	
		$('#btn-gg-buy').click(function(){
			var url = $(this).attr('href');			
			$.ajax({
				url:url,
				success:function(msg){
					alert(msg);
				}
			});			
			return false;
		});
});	
</script>
{{/if}}