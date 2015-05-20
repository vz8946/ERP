{{foreach from=$v item=vv key=kk}}
<div class="news-source-item source-item">
	<table width="100%">
		<tr>
			<td rowspan="6" width="100">{{html type="pic"
			name="source[news_data][$i][$j$kk][img]" id="pic-news-img-$i-$j-$kk" value=$vv.img}} </td>
		</tr>
		<tr>
			<td>
			<input type="hidden" name="source[news_data][{{$i}}][{{$j}}{{$kk}}][id]" value="{{$vv.id}}"/>
			名称：
			<input size="30" name="source[news_data][{{$i}}][{{$j}}{{$kk}}][title]" value="{{$vv.title}}"/>
			</td>
		</tr>
		<tr>
			<td>简述：
			<input size="30" name="source[news_data][{{$i}}][{{$j}}{{$kk}}][memo]" value="{{$vv.memo}}"/>
			</td>
		</tr>
		<tr>
			<td>URL：
			<input size="30" name="source[news_data][{{$i}}][{{$j}}{{$kk}}][url]" value="{{$vv.url}}"/>
			</td>
		</tr>
		<tr>
			<td>排序：
			<input name="source[news_data][{{$i}}][{{$j}}{{$kk}}][ord]" size="5" value="{{$vv.ord}}"/>
			</td>
		</tr>
		<tr>
			<td>
				可用：{{html type="slt" opt=$opt_enable   name="source[news_data][$i][$j$kk][enable]" value=$vv.enable}}
				&nbsp;&nbsp;
				操作： <input onclick="source_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
	</table>
</div>
{{/foreach}}
