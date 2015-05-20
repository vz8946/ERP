<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td>
			<input name="source[line_data][{{$i}}][name]" value="{{$v.name}}"/>
			<input onclick="data_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
		<tr>
			<th>配置：</th>
			<td>高度：
				<input name="source[line_data][{{$i}}][h]" value="{{$v.h}}" size='5'/>
				&nbsp;宽度：
				<input name="source[line_data][{{$i}}][w]" value="{{$v.w}}" size='5'/>
				&nbsp;颜色：
				<input name="source[line_data][{{$i}}][c]" value="{{$v.c}}" size="8"/>
			</td>
		</tr>
	</table>
</div>
