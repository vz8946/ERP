<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td>
			<input name="source[value_data][{{$i}}][name]" value="{{$k}}"/>
			<input onclick="data_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
		<tr>
			<th>值：</th>
			<td>
				<input name="source[value_data][{{$i}}][value]" value="{{$v}}"/>
			</td>
		</tr>
	</table>
</div>
