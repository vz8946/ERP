{{foreach from=$list_goods item=v key=k}}
<div class="goods-source-item source-item">
	<table width="100%">
		<tr>
			<td rowspan="6" width="100">{{html type="pic" 
				name="source[goods_data][$i][$j$k][img]" id="pic-goods-img-$i-$j-$k" value=$v.goods_img}}
			</td>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="source[goods_data][{{$i}}][{{$j}}{{$k}}][goods_id]" value="{{$v.goods_id}}"/>
				名称：<input size="30" name="source[goods_data][{{$i}}][{{$j}}{{$k}}][goods_name]" value="{{$v.goods_name}}"/>
			</td>
		</tr>
		<tr>
			<td>
				功效：<input size="30" name="source[goods_data][{{$i}}][{{$j}}{{$k}}][goods_alt]" value="{{$v.goods_alt}}"/>
			</td>
		</tr>
		<tr>
			<td>URL：<input size="30" name="source[goods_data][{{$i}}][{{$j}}{{$k}}][url]"/></td>
		</tr>
		<tr>
			<td>
				市场价：<input name="source[goods_data][{{$i}}][{{$j}}{{$k}}][market_price]" size="5"  value="{{$v.market_price}}"/>
				销售价：<input name="source[goods_data][{{$i}}][{{$j}}{{$k}}][price]" size="5"  value="{{$v.price}}"/>
			</td>
		</tr>
		<tr>
			<td>
				排序：<input name="source[goods_data][{{$i}}][{{$j}}{{$k}}][ord]" size="5" value="0"/>&nbsp;				
				可用：{{html type="slt" opt=$opt_enable  name="source[goods_data][$i][$j$k][enable]" value="Y"}}
				&nbsp;&nbsp;
				操作： <input onclick="source_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
	</table>
</div>
{{/foreach}}
