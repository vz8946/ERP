<div class="link-source-item source-item">
	<table width="100%">
		<tr>
			<td rowspan="6">
				{{html type="pic" id="pic-link-$j-$i" name="source[link_data][$i][$j][img]" value=$vv.img}}
			</td>
		</tr>
		<tr>
			<td>名称：<input size="30" name="source[link_data][{{$i}}][{{$j}}][title]" value="{{$vv.title}}"/></td>
		</tr>
		<tr>
			<td>URL：<input size="30" name="source[link_data][{{$i}}][{{$j}}][url]" value="{{$vv.url}}"/></td>
		</tr>
		<tr>
			<td>备注：<input size="30" name="source[link_data][{{$i}}][{{$j}}][memo]" value="{{$vv.memo}}"/></td>
		</tr>
		<tr>
			<td>
				颜色：<input size="7" name="source[link_data][{{$i}}][{{$j}}][color]" value="{{$vv.color}}"/>
				&nbsp;
				排序：<input size="4" name="source[link_data][{{$i}}][{{$j}}][ord]" value="{{$vv.ord}}"/>
			</td>
		</tr>
		<tr>
			<td>
				可用：{{html type="slt" opt=$opt_enable  name="source[link_data][$i][$j][enable]" value=$vv.enable}}
				&nbsp;
				新窗口：{{html type="slt" opt=$opt_enable  name="source[link_data][$i][$j][is_new_win]" value=$vv.is_new_win|default:'N'}}
				&nbsp;
				<input type="button" value="删除" onclick="source_item_del(this);"/>
			</td>
		</tr>
	</table>
</div>
