	{{foreach from=$list_brand item=v key=k}}
<div class="brand-source-item source-item">
	<table width="100%">
		<tr>
			<td rowspan="5" width="100">{{html type="pic" 
				name="source[brand_data][$i][$j$k][img]" id="pic-brand-img-$i-$j-$k" value=$v.small_logo}}
			</td>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="source[brand_data][{{$i}}][{{$j}}{{$k}}][brand_id]" value="{{$v.brand_id}}"/>
				名称：<input size="30" name="source[brand_data][{{$i}}][{{$j}}{{$k}}][title]" value="{{$v.brand_name}}"/></td>
		</tr>
		<tr>
			<td>URL：<input size="30" name="source[brand_data][{{$i}}][{{$j}}{{$k}}][url]"/></td>
		</tr>
		<tr>
			<td>排序：<input name="source[brand_data][{{$i}}][{{$j}}{{$k}}][ord]" size="5" value="0"/></td>
		</tr>
		<tr>
			<td>
				可用：{{html type="slt" opt=$opt_enable   name="source[brand_data][$i][$j$k][enable]" value="Y"}}
				&nbsp;&nbsp;
				操作： <input onclick="source_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
	</table>
</div>
	{{/foreach}}
