{{if $links}}
<div id="prmt-gg" class="box" style="width:748px;height:251px;border: 1px solid #ddd; margin-bottom: 10px;">
<input id="hdn-prgg-goods-id" type="hidden" value="{{$data.goods_id}}"/>
	<div class="b-t">推荐商品组合</div>
	<div class="b-c">
		<div class="goods-item">
			<div>
				<a target="_blank" href="/goods-{{$data.goods_id}}.html"><img
					width="120"
					src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_380_380.'}}" /></a>
			</div>
			<div style="line-height:16px;height:54px;overflow: hidden;">
				<a title="{{$data.goods_name}}" target="_blank" href="/goods-{{$data.goods_id}}.html">{{$data.goods_name}}</a>
			</div>
		</div>

		<div style="float:left;width:360px;{{if $links|@count > 2}}overflow-x:scroll;{{/if}}">
			<div style="width:{{math equation="x * y" x=180 y=$links|@count}}px">            
			{{foreach from=$links item=v key=k}} 
			{{if $v.goods_id != $data.goods_id}}
			<div class="lfloat goods-item goods-link">
				<div>
					<a target="_blank" href="/goods-{{$v.goods_id}}.html"><img
						width="120"
						src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_380_380.'}}" /></a>
				</div>
				<div style="line-height: 14px; height: 30px;overflow: hidden;">
					<a title="{{$v.goods_name}}" target="_blank" href="/goods-{{$v.goods_id}}.html">{{$v.goods_name}}</a>
				</div>
				<div>
					<label> 
						<span><input onclick="slt_prmt_goods(this,{{$v.price}});"
						 	autocomplete="off"
							type="checkbox" value="{{$v.goods_id}}" />&nbsp;</span> 
						<span style="font-weight: bold; color: red;">{{$v.price}}</span>￥
					</label>
				</div>
			</div>
			{{/if}} {{/foreach}}
		</div></div>

		<div class="group-info"
			style="width: 120px; height: 100px; padding-top: 30px;">
			<span>已选择 <span id="tip-pg-count">0</span> 个商品</span><br /> 搭 配 价：￥ <span id="tip-pg-price">{{$data.price}}</span><br />
			<div style="height: 5px; overflow: hidden;"></div>
			<a id="btn-prgg-buy" class="btn-gg-bug" href="javascript:void(0);">立即购买</a>
		</div>
		
		<div style="clear: both;"></div>
		
	</div>

</div>

<script>
$(function(){	
	   $('#btn-prgg-buy').click(function(){		
		var data = {};
		data.arr_goods_id = new Array();
		data.arr_goods_id.push($('#hdn-prgg-goods-id').val());
		$(this).parents('.box').find('input[type=checkbox]:checked').each(function(i,n){
			data.arr_goods_id.push($(n).val());
		});
		
		$.ajax({
			url:'/flow/actbuy-batch',
			data:data,
			dataType:'json',
			async:false,
			success:function(msg){
				if(msg.status == 'succ'){
					$.get('/index/cart/r/1', function(data) {
						$("#topCartNum").html(data.number);
						var content = '<div id="msg1" style="text-align:center;"><span style=" color:#1969CC;">该商品已成功放入购物车</span></div>' + data.html + '<div id="checknow" style="text-align:center;"><a href="/flow/index"><img src="/_static/images/shop/btn_check_right_now.jpg" /></a>&nbsp;&nbsp;<a style="position:relative;top:-12px;" href="javascript:closePopOutBox();">继续购物&raquo;&raquo;</a></div>';
						popOutBox(content,'加入购物车提示',450);								
						$('.top_cart').hide();
						$('.more').hide();
					},'json');
				}else{
					alert(msg.msg);
				}
			}
		});
	});
});

function slt_prmt_goods(elt,price){
	price = parseFloat(price);
	var pg_count = Number($('#tip-pg-count').html());
	var sprice = parseFloat($('#tip-pg-price').text());
	
	if(elt.checked){
		$('#tip-pg-price').text((sprice+price).toFixed(2));
		$('#tip-pg-count').html(pg_count+1);
	}else{
		$('#tip-pg-price').text((sprice-price).toFixed(2));
		$('#tip-pg-count').html(pg_count-1);
	}
}
</script>
{{/if}}