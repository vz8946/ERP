	{{foreach from=$v item=vv key=kk}}
<div class="brand-source-item source-item">
	<table width="100%">
		<tr>
			<td rowspan="5" width="100">{{html type="pic" 
				name="source[brand_data][$i][$j$kk][img]" id="pic-brand-img-$i-$j-$kk" value=$vv.img}}
			</td>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="source[brand_data][{{$i}}][{{$j}}{{$kk}}][brand_id]" value="{{$vv.brand_id}}"/>
				名称：<input size="30" name="source[brand_data][{{$i}}][{{$j}}{{$kk}}][title]" value="{{$vv.title}}"/></td>
		</tr>
		<tr>
			<td>URL：<input size="30" name="source[brand_data][{{$i}}][{{$j}}{{$kk}}][url]" value="{{$vv.url}}"/></td>
		</tr>
		<tr>
			<td>排序：<input name="source[brand_data][{{$i}}][{{$j}}{{$kk}}][ord]" size="5" value="{{$vv.ord}}"/></td>
		</tr>
		<tr>
			<td>
				可用：{{html type="slt" opt=$opt_enable  name="source[brand_data][$i][$j$kk][enable]"}}
				&nbsp;&nbsp;
				操作： <input onclick="source_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
	</table>
</div>
	{{/foreach}}
