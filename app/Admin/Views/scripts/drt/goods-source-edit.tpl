{{foreach from=$v item=vv key=kk}}
<div class="goods-source-item source-item">
	<table width="100%">
		<tr>
			<td rowspan="6" width="100">{{html type="pic"
			name="source[goods_data][$i][$j$kk][img]" id="pic-goods-img-$i-$j-$kk" value=$vv.img}} </td>
		</tr>
		<tr>
			<td>
			<input type="hidden" name="source[goods_data][{{$i}}][{{$j}}{{$kk}}][goods_id]" value="{{$vv.goods_id}}"/>
			名称：
			<input size="30" name="source[goods_data][{{$i}}][{{$j}}{{$kk}}][goods_name]" value="{{$vv.goods_name}}"/>
			</td>
		</tr>
		<tr>
			<td>功效：
			<input size="30" name="source[goods_data][{{$i}}][{{$j}}{{$kk}}][goods_alt]" value="{{$vv.goods_alt}}"/>
			</td>
		</tr>
		<tr>
			<td>URL：
			<input size="30" name="source[goods_data][{{$i}}][{{$j}}{{$kk}}][url]" value="{{$vv.url}}"/>
			</td>
		</tr>
		<tr>
			<td>
				市场价：<input name="source[goods_data][{{$i}}][{{$j}}{{$kk}}][market_price]" size="5" value="{{$vv.market_price}}"/>
				&nbsp;&nbsp;
				销售价：<input name="source[goods_data][{{$i}}][{{$j}}{{$kk}}][price]" size="5" value="{{$vv.price}}"/>
			</td>
		</tr>
		<tr>
			<td>
				排序：
				<input name="source[goods_data][{{$i}}][{{$j}}{{$kk}}][ord]" size="5" value="{{$vv.ord}}"/>&nbsp;
				可用：{{html type="slt" opt=$opt_enable   name="source[goods_data][$i][$j$kk][enable]" value=$vv.enable}}
				&nbsp;&nbsp;
				操作： <input onclick="source_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
	</table>
</div>
{{/foreach}}
